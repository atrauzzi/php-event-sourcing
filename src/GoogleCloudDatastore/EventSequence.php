<?php namespace Atrauzzi\PhpEventSourcing\GoogleCloudDatastore {

	use GDS\Entity;


	class EventSequence extends Entity {

		/** @var int */
		protected $value;

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