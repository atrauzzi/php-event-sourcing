<?php namespace Atrauzzi\PhpEventSourcing {


	abstract class AggregateRoot extends Entity {

		/** @var array */
		private $events = [];

		/** @var \Atrauzzi\PhpEventSourcing\Event[] */
		private $uncommittedEvents = [];

		/** @var int */
		private $lastSequence = -1;

		/** @var string|int */
		private $id;

		/**
		 * @return string
		 */
		public abstract function getType();

		/**
		 * @param \Atrauzzi\PhpEventSourcing\Event[] $events
		 */
		public function absorb(array $events) {
			foreach($events as $event) {
				$this->handleRecursively($event);
				++$this->lastSequence;
			}
		}

		/**
		 * @param \Atrauzzi\PhpEventSourcing\Event $event
		 */
		public function apply(Event $event) {
			$this->handleRecursively($event);
			++$this->lastSequence;
			$this->uncommittedEvents[] = $event;
		}

		/**
		 * @return array
		 */
		public function getUncommittedEvents() {
			return $this->uncommittedEvents;
		}

		/**
		 * @return int|string
		 */
		public function getId() {
			return $this->id;
		}

		/**
		 * @return int
		 */
		public function getLastSequence() {
			return $this->lastSequence;
		}

	}

}