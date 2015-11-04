<?php namespace App\Domain\GoogleCloudDatastore {

	use GDS\Store;
	use Atrauzzi\PhpEventSourcing\EventRepository as EventRepositoryContract;
	//
	use GDS\Schema;
	use Atrauzzi\PhpEventSourcing\GoogleCloudDatastore\EventSequence;
	use Atrauzzi\PhpEventSourcing\GoogleCloudDatastore\Event;
	use Atrauzzi\PhpEventSourcing\Exception\OptimisticConcurrency as OptimisticConcurrencyException;


	class EventRepository implements EventRepositoryContract {

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
					->addString('type')
					->addString('aggregate_type')
					->addInteger('aggregate_id')
					->addInteger('revision')
					->addString('data', false)
					->addDatetime('created')
			);

		}

		/**
		 * @param \Atrauzzi\PhpEventSourcing\Event[]|\Atrauzzi\PhpEventSourcing\Event $events
		 * @throws \Atrauzzi\PhpEventSourcing\Exception\OptimisticConcurrency
		 */
		public function save($events) {

			/** @var \Atrauzzi\PhpEventSourcing\Event[] $events */
			$events = (array)$events;

			// We must insert every event individually.
			foreach($events as $event) {

				$this->eventStore->beginTransaction();

				$sequenceKey = sprintf('%s%s',
					$event->getAggregateRootType(),
					$event->getAggregateRootId()
				);

				/** @var \Atrauzzi\PhpEventSourcing\GoogleCloudDatastore\EventSequence|null $sequence */
				$sequence = $this->sequenceStore->fetchByName($sequenceKey);

				if($sequence) {

					$currentSequence = $sequence->getValue();
					$expectedSequence = $event->getSequence();

					if($currentSequence >= $expectedSequence)
						throw new OptimisticConcurrencyException($event, $currentSequence);

					$sequence->setValue($expectedSequence);

				}
				else {
					$sequence = new EventSequence();
					$sequence->setValue(0);
				}

				$this->sequenceStore->upsert($sequence);
				$this->eventStore->upsert($event);

			}

		}

		/**
		 * Filters allows parameters to the query to be leaky and helps keep the signature tame.
		 *
		 * @param string|int $id
		 * @param array $filters
		 * @return \Atrauzzi\PhpEventSourcing\Event[]
		 */
		public function findByAggregateRootId($id, array $filters = []) {
			// TODO: Implement findByAggregateRootId() method.
		}
	}

}