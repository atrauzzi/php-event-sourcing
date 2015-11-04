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
		 */
		public function save($events);

		/**
		 * Filters allows parameters to the query to be leaky and helps keep the signature tame.
		 *
		 * @param string|int $id
		 * @param array $filters
		 * @return \Atrauzzi\PhpEventSourcing\Event[]
		 */
		public function findByAggregateRootId($id, array $filters = []);

	}

}