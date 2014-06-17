<?php

namespace DI\Serializers;

abstract class ObjectSerializer implements ISerializer
{
	public function fromArray(array $configuration)
	{
		$object = $this->makeObject($configuration);
		$this->setProperties($object, $configuration);
		return $object;
	}

	public function toArray($component)
	{
		return $this->getProperties($component);
	}

	abstract protected function makeObject(array $configuration);

	protected function setProperties($object, array $objectConfig)
	{
		foreach ($this->getPropertyConfigs() as $propertyName => $propertyConfig)
		{
			if ($this->isPropertySet($propertyName, $objectConfig))
				$this->setProperty($object, $propertyConfig['set'], $objectConfig[$propertyName]);
		}
	}

	abstract protected function getPropertyConfigs();

	private function isPropertySet($propertyName, array $objectConfig)
	{
		return array_key_exists($propertyName, $objectConfig);
	}

	private function setProperty($object, $propertySetter, $propertyValue)
	{
		if (method_exists($this, $methodName = 'set' . ucfirst($propertySetter)))
			$this->$methodName($object, $propertyValue);
		else if (is_array($propertyValue))
			$this->setArrayProperty($object, $propertySetter, $propertyValue);
		else
			$this->setSimpleProperty($object, $propertySetter, $propertyValue);
	}

	private function setArrayProperty($object, $propertyName, $propertyValue)
	{
		foreach ($propertyValue as $value)
			$object->$propertyName($value);
	}

	private function setSimpleProperty($object, $propertyName, $propertyValue)
	{
		if ($propertyValue)
			$object->$propertyName($propertyValue);
	}

	private function getProperties($object)
	{
		$result = $this->getObjectProperties($object);
		foreach ($this->getPropertyConfigs() as $propertyName => $propertyConfig)
		{
			if (($propertyValue = $this->getProperty($object, $propertyConfig['get'])) !== $propertyConfig['default'])
				$result[$propertyName] = $propertyValue;
		}
		return $result;
	}

	protected function getObjectProperties($object)
	{
		return array();
	}

	private function getProperty($object, $propertyGetter)
	{
		if (method_exists($this, $methodName = 'get' . ucfirst($propertyGetter)))
			return $this->$methodName($object);
		return $object->$propertyGetter();
	}
}
