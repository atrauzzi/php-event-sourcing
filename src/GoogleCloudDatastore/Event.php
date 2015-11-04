<?php namespace Atrauzzi\PhpEventSourcing\GoogleCloudDatastore {

	use GDS\Entity;
	use Atrauzzi\PhpEventSourcing\Event as EventContract;


	class Event extends Entity implements EventContract {

		/** @var string|int */
		private $id;

		/** @var string|int */
		private $aggregateRootId;

		/** @var string|int */
		private $aggregateRootType;

		/** @var int */
		private $sequence;

		/**
		 * @return int|string
		 */
		public function getId() {
			return $this->id;
		}

		/**
		 * @return int|string
		 */
		public function getAggregateRootId() {
			return $this->aggregateRootId;
		}

		/**
		 * @return int|string
		 */
		public function getAggregateRootType() {
			return $this->aggregateRootType;
		}

		/**
		 * @return int
		 */
		public function getSequence() {
			return $this->sequence;
		}

	}

}