<?php namespace Atrauzzi\PhpEventSourcing {


	abstract class Repository {

		/** @var \Atrauzzi\PhpEventSourcing\EventRepository */
		private $eventRepository;

		/**
		 * @param \Atrauzzi\PhpEventSourcing\EventRepository $eventRepository
		 */
		public function __construct(EventRepository $eventRepository) {
			$this->eventRepository = $eventRepository;
		}

		/**
		 * @return string
		 */
		public abstract function getAggregateRootClass();

		/**
		 * @param int $id
		 * @return \Atrauzzi\PhpEventSourcing\AggregateRoot
		 */
		public function findById($id) {

			$id = intval($id);
			$aggregateRootClass = $this->getAggregateRootClass();

			$events = $this->eventRepository->find($aggregateRootClass, $id);

			/** @var \Atrauzzi\PhpEventSourcing\AggregateRoot $aggregateRoot */
			$aggregateRoot = new $aggregateRootClass();
			$aggregateRoot->absorb($events);

			return $aggregateRoot;

		}

		/**
		 * @param \Atrauzzi\PhpEventSourcing\AggregateRoot $aggregateRoot
		 * @throws \Atrauzzi\PhpEventSourcing\Exception\OptimisticConcurrency
		 */
		public function save(AggregateRoot $aggregateRoot) {
			$this->eventRepository->save($aggregateRoot->getUncommittedEvents());
		}

	}

}