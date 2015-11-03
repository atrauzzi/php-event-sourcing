<?php namespace App\Domain\GoogleCloudDatastore {

	use GDS\Store;
	use App\Domain\EventSource\EventRepository as EventRepositoryContract;
	//
	use App\Domain\GoogleCloudDatastore\Event;
	use GDS\Schema;


	class EventRepository extends Store implements EventRepositoryContract {

		public function __construct() {

			$schema = (new Schema('Event'))
				->setEntityClass(Event::class)
				->addString('type')
				->addString('aggregate_type')
				->addInteger('aggregate_id')
				->addInteger('revision')
				->addString('data')
				->addDatetime('created')
			;

			parent::__construct($schema);

		}

		/**
		 * @param \App\Domain\EventSource\Event[] $events
		 */
		public function save(array $events) {

			$this->upsert($events);
			
		}

		/**
		 * Filters allows parameters to the query to be leaky and helps keep the signature tame.
		 *
		 * @param string|int $id
		 * @param array $filters
		 * @return \App\Domain\EventSource\Event[]
		 */
		public function findByAggregateRootId($id, array $filters = []) {
			// TODO: Implement findByAggregateRootId() method.
		}
	}

}