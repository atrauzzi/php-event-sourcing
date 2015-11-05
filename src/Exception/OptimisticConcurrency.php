<?php namespace Atrauzzi\PhpEventSourcing\Exception {

	use \Exception;
	//
	use Atrauzzi\PhpEventSourcing\AggregateRoot;


	class OptimisticConcurrency extends Exception {

		/** @var \Atrauzzi\PhpEventSourcing\AggregateRoot */
		protected $aggregateRoot;

		/** @var object */
		protected $event;

		/**
		 * @param \Atrauzzi\PhpEventSourcing\AggregateRoot $aggregateRoot
		 * @param object $event
		 */
		public function __construct(AggregateRoot $aggregateRoot, $event) {

			$this->aggregateRoot = $aggregateRoot;

			$this->event = $event;

			parent::__construct(sprintf('The data for `%s:%s` has changed, sequence at %s, expected %s.',
				$aggregateRoot->getType(),
				$aggregateRoot->getId(),
				$aggregateRoot->getLastSequence()
			));

		}

		/**
		 * @return \Atrauzzi\PhpEventSourcing\Event
		 */
		public function getEvent() {
			return $this->event;
		}

		/**
		 * @return \Atrauzzi\PhpEventSourcing\AggregateRoot
		 */
		public function getAggregateRoot() {
			return $this->aggregateRoot;
		}

	}

}