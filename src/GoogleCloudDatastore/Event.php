<?php namespace Atrauzzi\PhpEventSourcing\GoogleCloudDatastore {

	use GDS\Entity;
	//
	use Atrauzzi\PhpEventSourcing\AggregateRoot;


	class Event extends Entity {

		/**
		 * @return int|string
		 */
		public function getAggregateRootId() {
			return $this->aggregate_root_id;
		}

		/**
		 * @return int|string
		 */
		public function getAggregateRootType() {
			return $this->aggregate_root_type;
		}

		/**
		 * @return null
		 */
		public function getPhpDiscriminator() {
			return $this->discriminator_php;
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