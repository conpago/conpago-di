<?php

namespace Saigon\Conpago\DI\Resolvables;

use Saigon\Conpago\DI\DefaultParameter;
use Saigon\Conpago\DI\Implementation\IResolver;
use Saigon\Conpago\DI\NamedParameter;
use Saigon\Conpago\DI\Parameter;

class ParameterList
{
	public function __construct($className, $defaultParameters)
	{
		$this->className = $className;
		$this->defaultParameters = $defaultParameters;
	}

	public function resolve(IResolver $container, $parameters)
	{
		if (!$this->hasCollectedParameters())
			$this->collectParameters();
		return $this->resolveParameters($container, $parameters);
	}

	private function hasCollectedParameters()
	{
		return is_array($this->params);
	}

	private function resolveParameters(IResolver $container, $parameters)
	{
		$args = array();
		foreach ($this->params as $parameter)
		{
			$next = each($parameters);
			if ($next === false || $next[1] instanceof DefaultParameter)
				$args[] = $parameter->resolve($container);
			else if ($next !== false && $next[1] instanceof NamedParameter)
				$args[] = $container->resolveNamed($next[1]->getName());
			else
				$args[] = $next[1];
		}
		return $args;
	}

	private function collectParameters()
	{
		$this->params = array();
		$rc = new \ReflectionClass($this->className);
		$constructor = $rc->getConstructor();
		if ($constructor)
			$this->collectParametersFrom($constructor);
	}

	private function collectParametersFrom(\ReflectionMethod $constructor)
	{
		$params = $this->defaultParameters;
		foreach ($constructor->getParameters() as $parameter)
		{
			$next = each($params);
			$overridenValue = $next !== false ? $next[1] : Parameter::missing();
			$this->params[] = $this->resolveParameterType($parameter, $overridenValue);
		}
	}

	private function resolveParameterType(\ReflectionParameter $parameterInfo, $overridenValue)
	{
		foreach (self::getParameterClassNames() as $className)
		{
			$parameter = $this->tryCreateParameter($className . 'Parameter', $parameterInfo, $overridenValue);
			if ($parameter)
				return $parameter;
		}
		throw new \LogicException('Something went wrong');
	}

	private static function getParameterClassNames()
	{
		return array('Meta', 'Lazy', 'Factory', 'Standard', 'Typed', 'Named', 'Custom');
	}

	private function tryCreateParameter($className, $parameterInfo, $overridenValue)
	{
		$qualifiedClassName = 'Saigon\\Conpago\\DI\\Parameters\\' . $className;
		return $qualifiedClassName::tryCreate($parameterInfo, $overridenValue);
	}

	private $className;
	private $defaultParameters;
	private $params;
}
