<?php

namespace DI\Parameters;

use DI\Implementation\IResolver;
use DI\Transformers\DirectTransformer;

class NamedParameter implements IParameter
{
	protected function __construct($target = null)
	{
		$this->targetName = $target;
	}

	public static function tryCreate(\ReflectionParameter $parameter, $overridenValue)
	{
		if (!($overridenValue instanceof \DI\NamedParameter))
			return null;

		return new NamedParameter($overridenValue->getName());
	}

	public function resolve(IResolver $container)
	{
		return $container->resolveWith('Name: ' . $this->targetName, DirectTransformer::def());
	}

	public function getTargetName()
	{
		return $this->targetName;
	}

	public function isArray()
	{
		return false;
	}

	private $targetName;
}
