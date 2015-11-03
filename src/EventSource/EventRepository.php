<?php namespace App\Domain\EventSource {

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
		 * @param \App\Domain\EventSource\Event[] $events
		 */
		public function save(array $events);

		/**
		 * Filters allows parameters to the query to be leaky and helps keep the signature tame.
		 *
		 * @param string|int $id
		 * @param array $filters
		 * @return \App\Domain\EventSource\Event[]
		 */
		public function findByAggregateRootId($id, array $filters = []);

	}

}