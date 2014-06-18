<?php

namespace Saigon\Conpago\DI;

use Saigon\Conpago\DI\Implementation\Intermediate;

/**
 * Class Factory
 * Represents an object factory
 *
 * @package DI
 */
class Factory extends Intermediate
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
			Transformers\DirectTransformer::def(), $args);
	}
}
