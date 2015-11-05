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
		 * Feel free to override this if you want your type names to look different.
		 *
		 * @return string
		 */
		public static function getType() {
			return snake_case((new \ReflectionClass(self::class))->getShortName());
		}

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
		 * @return \Atrauzzi\PhpEventSourcing\Event[]
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