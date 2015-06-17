<?php

namespace Conpago\DI\Parameters;

use Conpago\DI\Implementation\IResolver;
use Conpago\DI\Transformers\DirectTransformer;

class NamedParameter implements IParameter
{
	protected function __construct($target = null)
	{
		$this->targetName = $target;
	}

	public static function tryCreate($overridenValue)
	{
		if (!($overridenValue instanceof \Conpago\DI\NamedParameter))
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
