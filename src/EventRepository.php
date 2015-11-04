<?php namespace Atrauzzi\PhpEventSourcing {

	/**
	 * Interface EventRepository
	 *
	 * The base entity repository and its subclasses are going to expect to have
	 * access to an implementation of this interface.
	 *
	 * @package App\Domain\EventSource
	 */
	interface EventRepository {

		/**
		 * @param \Atrauzzi\PhpEventSourcing\Event[]|\Atrauzzi\PhpEventSourcing\Event $events
		 * @throws \Atrauzzi\PhpEventSourcing\Exception\OptimisticConcurrency
		 */
		public function save($events);

		/**
		 * @param string $aggregateType
		 * @param int|string $aggregateId
		 * @return \Atrauzzi\PhpEventSourcing\Entity[]
		 */
		public function find($aggregateType, $aggregateId);

	}

}