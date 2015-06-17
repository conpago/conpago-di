<?php

namespace Conpago\DI\Serializers;

use Conpago\DI\Registerers\TypeRegisterer;

class TypeSerializer extends ObjectSerializer
{
	protected function makeObject(array $configuration)
	{
		return new TypeRegisterer($configuration['typeName']);
	}

	protected function getObjectProperties($object)
	{
		return array(
			'typeName' => $object->getTypeName(),
		);
	}

	protected function getPropertyConfigs()
	{
		return self::$config;
	}

	protected function setWithParams(TypeRegisterer $registerer, $propertyValue)
	{
		foreach ($propertyValue as $param)
			$registerer->withParams($this->paramFromArray($param));
	}

	private function paramFromArray($param)
	{
		return Serializer::getInstance()->fromArray($param);
	}

	protected function getWithParams(TypeRegisterer $registerer)
	{
		return $this->paramsToArray($registerer->getParams());
	}

	private function paramsToArray($params)
	{
		$result = array();
		foreach ($params as $param)
			$result[] = $this->paramToArray($param);
		return $result;
	}

	private function paramToArray($param)
	{
		return Serializer::getInstance()->toArray($param);
	}

	private static $config = array(
		'asBases' =>		array('get' => 'isAsBases',			'set' => 'asBases',			'default' => false),
		'asInterfaces' =>	array('get' => 'isAsInterfaces',	'set' => 'asInterfaces',	'default' => false),
		'asSelf' =>			array('get' => 'isAsSelf',			'set' => 'asSelf',			'default' => false),
		'singleInstance' =>	array('get' => 'isSingleInstance',	'set' => 'singleInstance',	'default' => false),
		'aliases' =>		array('get' => 'getAliases',		'set' => 'asA',				'default' => array()),
		'names' =>			array('get' => 'getNames',			'set' => 'named',			'default' => array()),
		'key' =>			array('get' => 'getKey',			'set' => 'keyed',			'default' => null),
		'params' =>			array('get' => 'withParams',		'set' => 'withParams',		'default' => array()),
	);
}
