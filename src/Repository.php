<?php namespace Atrauzzi\PhpEventSourcing {

	/**
	 * Interface Repository
	 *
	 * Aggregate Root repositories are going to expect to have access to an
	 * implementation of this interface.
	 *
	 * @package App\Domain\EventSource
	 */
	interface Repository {

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
		 * @param string|\Atrauzzi\PhpEventSourcing\AggregateRoot $aggregateType
		 * @param int|string $aggregateId
		 * @return \Atrauzzi\PhpEventSourcing\AggregateRoot
		 */
		public function find($aggregateType, $aggregateId);

	}

}