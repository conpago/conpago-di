<?php

namespace Conpago\DI\Serializers;

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
		if ($componentClass == '\Conpago\\DI\\Registerers\\TypeRegisterer')
			return new TypeSerializer();
		if ($componentClass == '\Conpago\\DI\\Registerers\\InstanceRegisterer')
			return new InstanceSerializer();
		if ($componentClass == '\Conpago\\DI\\Registerers\\ClosureRegisterer')
			return new ClosureSerializer();
		if ($componentClass == '\Conpago\\DI\\NamedParameter')
			return new NamedParameterSerializer();
		if ($componentClass == '\Conpago\\DI\\DefaultParameter')
			return new DefaultParameterSerializer();
		throw new \RuntimeException("Unknown component type: {$componentClass}");
	}

	private static $serializers;
	private static $instance;
}
