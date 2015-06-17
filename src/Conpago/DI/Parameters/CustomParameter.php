<?php

namespace Conpago\DI\Parameters;

use Conpago\DI\DefaultParameter;
use Conpago\DI\Implementation\IResolver,
	Conpago\DI\Exceptions\MissingParameterException;
use Conpago\DI\MissingParameter;

class CustomParameter implements IParameter
{
	protected function __construct(\ReflectionParameter $parameter, $overridenValue)
	{
		if (!($overridenValue instanceof MissingParameter) && !($overridenValue instanceof DefaultParameter))
			$this->setDefaultValue($overridenValue);
		else if ($parameter && $parameter->isOptional())
			$this->setDefaultValue($parameter->getDefaultValue());
	}

	public static function tryCreate(\ReflectionParameter $parameter, $overridenValue)
	{
		return new CustomParameter($parameter, $overridenValue);
	}

	private function setDefaultValue($value)
	{
		$this->defaultValue = $value;
		$this->hasDefaultValue = true;
	}

	public function hasDefaultValue()
	{
		return $this->hasDefaultValue;
	}

	public function getDefaultValue()
	{
		return $this->defaultValue;
	}

	public function resolve(IResolver $container)
	{
		if ($this->hasDefaultValue())
			return $this->getDefaultValue();
		throw new MissingParameterException;
	}

	public function getTargetName()
	{
		throw new \LogicException('Custom parameter does not have a type specified.');
	}

	public function isArray()
	{
		return false;
	}

	private $defaultValue;
	private $hasDefaultValue = false;
}
