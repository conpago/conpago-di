<?php

	namespace Conpago\DI\Implementation;

	use Conpago\DI\IFactory;
	use Conpago\DI\Transformers\DirectTransformer;

	/**
	 * Class Factory
	 * Represents an object factory
	 *
	 * @package DI
	 */
	class Factory extends Intermediate implements IFactory
	{
		/**
		 * Resolves object from container and return instance
		 *
		 * @return null|object
		 */
		public function createInstance()
		{
			$args = func_get_args();

			return $this->getTarget()->resolve($this->getContainer(),
				DirectTransformer::def(),
				$args);
		}
	}
