<?php namespace App\Domain\EventSource {

	/**
	 * Class Entity
	 *
	 * This class represents any entity participating in an aggregate root graph.  Subclasses are
	 * responsible for providing event apply methods and listing their child entities.
	 *
	 * @package App\Domain\Esgds
	 */
	abstract class Entity {

		/** @var \App\Domain\EventSource\AggregateRoot */
		private $aggregateRoot;


		/**
		 * Events must originate from our root, so start from there.
		 *
		 * @param \App\Domain\EventSource\Event $event
		 */
		public function apply(Event $event) {
			$this->aggregateRoot->apply($event);
		}

		/**
		 * By default an entity has no children.  Override this method in your subclass if an entity is
		 * responsible for propagating events to its children.
		 *
		 * @return \App\Domain\EventSource\Entity[]
		 */
		public function getChildEntities() {
			return [];
		}

		//
		//

		/**
		 * @param \App\Domain\EventSource\Event $event
		 */
		protected function handleRecursively(Event $event) {

			$this->handle($event);

			foreach($this->getChildEntities() as $entity)
				$entity->handleRecursively($event);

		}

		/**
		 * @param \App\Domain\EventSource\AggregateRoot $aggregateRoot
		 */
		protected function setAggregateRoot(AggregateRoot $aggregateRoot) {

			if($this->aggregateRoot) return;
			if($aggregateRoot === $this) return;

			$this->aggregateRoot = $aggregateRoot;

		}

		//
		//

		/**
		 * @param \App\Domain\EventSource\Event $event
		 */
		private function handle(Event $event) {

			$method = $this->getApplyMethod($event);

			if(method_exists($this, $method))
				$this->$method($event);

		}

		/**
		 * @param \App\Domain\EventSource\Event $event
		 * @return string
		 */
		private function getApplyMethod(Event $event) {
			$classParts = explode('\\', get_class($event));
			return 'apply' . end($classParts);
		}

	}

}