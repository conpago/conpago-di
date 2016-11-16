<?php

namespace Conpago\DI\Serializers;

use Conpago\DI\DefaultParameter;
use Conpago\DI\NamedParameter;
use Conpago\DI\Registerers\ClosureRegisterer;
use Conpago\DI\Registerers\InstanceRegisterer;
use Conpago\DI\Registerers\TypeRegisterer;

class Serializer implements ISerializer
{
	/**
	 * @return Serializer
	 */
	public static function getInstance()
	{
		if (!self::$instance)
			self::$instance = new Serializer();
		return self::$instance;
	}

	public function toArray($component)
	{
		if (is_object($component))
			return $this->objectToArray($component);
		return $this->valueToArray($component);
	}

	private function objectToArray($component)
	{
		$componentClass = get_class($component);
		$serializer = $this->getSerializerFor($componentClass);
		return array_merge(array(
			'componentClass' => $componentClass
		), $serializer->toArray($component));
	}

	private function valueToArray($value)
	{
		return array(
			'componentClass' => 'value',
			'value' => $value,
		);
	}

	public function fromArray(array $configuration)
	{
		if ($configuration['componentClass'] == 'value')
			return $this->valueFromArray($configuration);
		return $this->objectFromArray($configuration);
	}

	private function valueFromArray($configuration)
	{
		return $configuration['value'];
	}

	private function objectFromArray($configuration)
	{
		$serializer = $this->getSerializerFor($configuration['componentClass']);
		return $serializer->fromArray($configuration);
	}

	private function getSerializerFor($componentClass)
	{
		if (!isset(self::$serializers[$componentClass]))
			self::$serializers[$componentClass] = $this->makeSerializerFor($componentClass);
		return self::$serializers[$componentClass];
	}

	private function makeSerializerFor($componentClass)
	{
		if ($componentClass == TypeRegisterer::class)
			return new TypeSerializer();
		if ($componentClass == InstanceRegisterer::class)
			return new InstanceSerializer();
		if ($componentClass == ClosureRegisterer::class)
			return new ClosureSerializer();
		if ($componentClass == NamedParameter::class)
			return new NamedParameterSerializer();
		if ($componentClass == DefaultParameter::class)
			return new DefaultParameterSerializer();
		throw new \RuntimeException("Unknown component type: {$componentClass}");
	}

	private static $serializers;
	private static $instance;
}
