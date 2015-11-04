<?php namespace Atrauzzi\PhpEventSourcing\Exception {

	use \Exception;
	//
	use Atrauzzi\PhpEventSourcing\Event;


	class OptimisticConcurrency extends Exception {

		/** @var \Atrauzzi\PhpEventSourcing\Event */
		protected $event;

		/** @var int */
		protected $currentSequence;

		/**
		 * @param \Atrauzzi\PhpEventSourcing\Event $event
		 * @param int $currentSequence
		 */
		public function __construct(Event $event, $currentSequence) {

			$this->event = $event;
			$this->currentSequence = $currentSequence;

			parent::__construct(sprintf('The data for `%s:%s` has changed, sequence at %s, expected %s.',
				$event->getAggregateRootType(),
				$event->getAggregateRootId(),
				$currentSequence
			));

		}

		/**
		 * @return \Atrauzzi\PhpEventSourcing\Event
		 */
		public function getEvent() {
			return $this->event;
		}

		/**
		 * @return int
		 */
		public function getCurrentSequence() {
			return $this->currentSequence;
		}

	}

}