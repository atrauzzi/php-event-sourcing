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
		 * Saves all uncommitted events to to the underlying storage driver.
		 *
		 * @param \Atrauzzi\PhpEventSourcing\AggregateRoot $aggregateRoot
		 * @throws \Atrauzzi\PhpEventSourcing\Exception\OptimisticConcurrency
		 */
		public function save(AggregateRoot $aggregateRoot);

		/**
		 * Asks the underlying storage driver to reconstitute an aggregate root.
		 *
		 * @param string $phpDiscriminator
		 * @param int|string $id
		 * @return \Atrauzzi\PhpEventSourcing\AggregateRoot
		 */
		public function find($phpDiscriminator, $id);

	}

}