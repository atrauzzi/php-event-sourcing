<?php namespace Atrauzzi\PhpEventSourcing\GoogleCloudDatastore {

	use GDS\Entity;


	/**
	 * Class EventSequence
	 *
	 * Google Cloud Datastore doesn't support the notion of unique or auto-incrementing
	 * properties.  We implement this by storing sequences seperately and checking them
	 * during persistence during a transaction.
	 *
	 * @package Atrauzzi\PhpEventSourcing\GoogleCloudDatastore
	 */
	class EventSequence extends Entity {

		/**
		 * @return int
		 */
		public function getValue() {
			return $this->value;
		}

		/**
		 * @param int $value
		 */
		public function setValue($value) {
			$this->value = $value;
		}

	}

}