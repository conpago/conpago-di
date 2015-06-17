<?php

	namespace Saigon\Conpago\DI\Implementation;

	use Saigon\Conpago\DI\IFactory;
	use Saigon\Conpago\DI\Transformers\DirectTransformer;

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
