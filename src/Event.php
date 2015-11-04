<?php namespace Atrauzzi\PhpEventSourcing {


	interface Event {

		/**
		 * @param string $type
		 * @param \Atrauzzi\PhpEventSourcing\AggregateRoot $aggregateRoot
		 */
		public function __construct($type, AggregateRoot $aggregateRoot);

		/**
		 * @return int|string
		 */
		public function getAggregateRootId();

		/**
		 * @return int|string
		 */
		public function getAggregateRootType();

		/**
		 * @return array
		 */
		public function getData();

		/**
		 * @param array $data
		 */
		public function setData(array $data);

		/**
		 * @return int
		 */
		public function getSequence();

	}

}