<?php

namespace Conpago\DI\Serializers;

use Conpago\DI\Exceptions\SerializingClosureException;
use Conpago\DI\Registerers\ClosureRegisterer;

class ClosureSerializer extends ObjectSerializer
{
	public function toArray($component)
	{
		if (!is_string($component->getClosure()))
			throw new SerializingClosureException;
		return parent::toArray($component);
	}

	protected function makeObject(array $configuration)
	{
		return new ClosureRegisterer($configuration['code']);
	}

	protected function getObjectProperties($object)
	{
		return array(
			'code' => $object->getClosure(),
		);
	}

	protected function getPropertyConfigs()
	{
		return self::$config;
	}

	private static $config = array(
		'aliases' =>		array('get' => 'getAliases',		'set' => 'asA',				'default' => array()),
		'names' =>			array('get' => 'getNames',			'set' => 'named',			'default' => array()),
		'key' =>			array('get' => 'getKey',			'set' => 'keyed',			'default' => null),
		'singleInstance' =>	array('get' => 'isSingleInstance',	'set' => 'singleInstance',	'default' => false),
	);
}
