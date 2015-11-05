<?php namespace Atrauzzi\PhpEventSourcing\GoogleCloudDatastore {

	use GDS\Entity;
	//
	use Atrauzzi\PhpEventSourcing\AggregateRoot;


	class Event extends Entity {

		/** @var string */
		private $type;

		/** @var string|int */
		private $aggregateRootId;

		/** @var string|int */
		private $aggregateRootType;

		/** @var int */
		private $sequence;

		/**
		 * @param string $type
		 * @param \Atrauzzi\PhpEventSourcing\AggregateRoot $aggregateRoot
		 */
		public function __construct($type, AggregateRoot $aggregateRoot) {
			$this->type = $type;
			$this->aggregateRootId = $aggregateRoot->getId();
			$this->aggregateRootType = $aggregateRoot->getType();
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

		public function getPhpDiscriminator() {
			return $this->discriminatorPhp;
		}

		/**
		 * @return array
		 */
		public function getData() {
			return $this->data;
		}

		/**
		 * @param array $data
		 */
		public function setData(array $data) {
			$this->data = $data;
		}

		/**
		 * @return int
		 */
		public function getSequence() {
			return $this->sequence;
		}

		/**
		 * Returns all properties that belong to the event being persisted.
		 *
		 * @return array
		 */
		public function getEventData() {

			$eventProperties = [];

			foreach($this->getData() as $prefixedProperty => $value) {
				if(
					($property = preg_replace('/$event_/', '', $prefixedProperty))
					!= $prefixedProperty
				)
					$eventProperties[$property] = '';
			}

			return $eventProperties;

		}

	}

}