<?php namespace Atrauzzi\PhpEventSourcing\GoogleCloudDatastore {

	use Atrauzzi\PhpEventSourcing\AggregateRoot;
	use Atrauzzi\PhpEventSourcing\Repository as RepositoryContract;
	//
	use GDS\Schema;
	use GDS\Store;
	use Atrauzzi\PhpEventSourcing\GoogleCloudDatastore\EventSequence;
	use Atrauzzi\PhpEventSourcing\GoogleCloudDatastore\Event;
	use Atrauzzi\PhpEventSourcing\Exception\OptimisticConcurrency as OptimisticConcurrencyException;


	class Repository implements RepositoryContract {

		/** @var \GDS\Store */
		protected $sequenceStore;

		/** @var \GDS\Store */
		protected $eventStore;

		public function __construct() {

			$this->sequenceStore = new Store(
				(new Schema('EventSequence'))
				->setEntityClass(EventSequence::class)
				->addInteger('value')
			);

			$this->eventStore = new Store(
				(new Schema('Event'))
				->setEntityClass(Event::class)
				->addDatetime('created')
				->addString('type', false)
				->addInteger('sequence')
				->addString('aggregate_type')
				->addInteger('aggregate_id')
			);

		}

		/**
		 * @param \Atrauzzi\PhpEventSourcing\AggregateRoot $aggregateRoot
		 * @throws \Atrauzzi\PhpEventSourcing\Exception\OptimisticConcurrency
		 */
		public function save(AggregateRoot $aggregateRoot) {

			foreach($aggregateRoot->getUncommittedEvents() as $event) {

				$this->eventStore->beginTransaction();

				$sequenceKey = sprintf('%s%s',
					$aggregateRoot->getType(),
					$aggregateRoot->getId()
				);

				/** @var \Atrauzzi\PhpEventSourcing\GoogleCloudDatastore\EventSequence|null $sequence */
				$sequence = $this->sequenceStore->fetchByName($sequenceKey);

				if($sequence) {

					$currentSequence = $sequence->getValue();
					$desiredSequence = $aggregateRoot->getLastSequence() + 1;

					if($currentSequence >= $desiredSequence)
						throw new OptimisticConcurrencyException($aggregateRoot, $event);

					$sequence->setValue($desiredSequence);

				}
				else {
					$sequence = new EventSequence();
					$sequence->setValue(0);
				}

				$this->sequenceStore->upsert($sequence);
				$this->eventStore->upsert($this->createGdsEvent($aggregateRoot, $event));

			}

		}


		/**
		 * @param string|\Atrauzzi\PhpEventSourcing\AggregateRoot $aggregateType
		 * @param int|string $aggregateId
		 * @return \Atrauzzi\PhpEventSourcing\AggregateRoot
		 */
		public function find($aggregateType, $aggregateId) {

			$gdsEvents = $this->eventStore->fetchAll('SELECT * FROM Event WHERE aggregate_type = @aggregateType AND aggregate_id = @aggregateId ORDER BY sequence ASC', [
				$aggregateType::getType(),
				$aggregateId,
			]);

			/** @var \Atrauzzi\PhpEventSourcing\AggregateRoot $aggregateRoot */
			$aggregateRoot = new $aggregateType();
			$aggregateRoot->absorb($this->hydrateEvents($gdsEvents));

			return $aggregateRoot;

		}

		//
		//

		/**
		 * Produces a persistable GDS entity based on an aggregate root and an event.
		 *
		 * @param \Atrauzzi\PhpEventSourcing\AggregateRoot $aggregateRoot
		 * @param object $event
		 * @return \Atrauzzi\PhpEventSourcing\GoogleCloudDatastore\Event
		 */
		private function createGdsEvent(AggregateRoot $aggregateRoot, $event) {

			$gdsEvent = $this->eventStore->createEntity([
				'created' => new DateTime(),
				'type' => $this->getType($event),
				'sequence' => $aggregateRoot->getLastSequence() + 1,
				'aggregate_type' => $aggregateRoot->getType(),
				'aggregate_id' => $aggregateRoot->getId(),
				'discriminator_php' => get_class($event),
			]);

			// Extract all the POPO event's public properties and prefix them with `event_`.
			foreach(get_object_vars($event) as $property => $value) {
				$eventProperty = 'event_' . snake_case($property);
				$gdsEvent->$eventProperty = $value;
			}

			return $gdsEvent;

		}

		/**
		 * Returns a short, platform agnostic handle for the event.
		 *
		 * @param object $event
		 * @return string
		 */
		private function getType($event) {

			if(method_exists($event, 'getType'))
				return $event->getType();

			return snake_case((new \ReflectionClass($event))->getShortName());

		}

		/**
		 * Rebuilds events from GDS-backed event data.
		 *
		 * This method is heavy.
		 *
		 * @param \Atrauzzi\PhpEventSourcing\GoogleCloudDatastore\Event[] $gdsEvents
		 * @return object[]
		 */
		private function hydrateEvents(array $gdsEvents) {

			$events = [];

			foreach($gdsEvents as $gdsEvent) {

				$properties = [];
				foreach($gdsEvent->getEventData() as $property => $value)
					$properties[camel_case($property)] = $value;

				$eventClass = $gdsEvent->getPhpDiscriminator();
				$eventClassReflection = new \ReflectionClass($eventClass);
				$eventClassConstructor = $eventClassReflection->getConstructor();

				$constructorParameters = [];
				foreach($eventClassConstructor->getParameters() as $parameter)
					$constructorParameters[$parameter->getName()] = array_get($properties, $parameter->getName());

				$event = $eventClassReflection->newInstanceArgs($constructorParameters);
				$properties = array_forget($properties, $constructorParameters);

				foreach($eventClassReflection->getProperties() as $property)
					$property->setValue($event, array_get($properties, $property->getName()));

				$events[] = $event;

			}

			return $events;

		}

	}

}