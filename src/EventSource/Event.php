<?php namespace App\Domain\EventSource {


	interface Event {

		/**
		 * @return int|string
		 */
		public function getId();

		/**
		 * @return int|string
		 */
		public function getAggregateRootId();

		/**
		 * @return int|string
		 */
		public function getAggregateRootType();

		/**
		 * @return int
		 */
		public function getSequence();

	}

}