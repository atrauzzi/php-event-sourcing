<?php namespace App\Domain\Esgds {

	use App\Domain\EventSource\Event;


	abstract class AggregateRoot extends Entity {

		/** @var array */
		private $events = [];

		/** @var \App\Domain\EventSource\Event[] */
		private $uncommittedEvents = [];

		/** @var int */
		private $lastSequence = -1;

		/** @var string|int */
		private $id;

		/**
		 * @param \App\Domain\EventSource\Event[] $events
		 */
		public function absorb(array $events) {
			foreach($events as $event) {
				$this->handleRecursively($event);
				++$this->lastSequence;
			}
		}

		/**
		 * @param \App\Domain\EventSource\Event $event
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