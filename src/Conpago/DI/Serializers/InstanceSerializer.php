<?php

namespace Saigon\Conpago\DI\Serializers;

use Saigon\Conpago\DI\Registerers\InstanceRegisterer;

class InstanceSerializer extends ObjectSerializer
{
	protected function makeObject(array $configuration)
	{
		return new InstanceRegisterer(unserialize($configuration['object']));
	}

	protected function getObjectProperties($object)
	{
		return array(
			'object' => serialize($object->getInstance()),
		);
	}

	protected function getPropertyConfigs()
	{
		return self::$config;
	}

	private static $config = array(
		'asSelf' =>			array('get' => 'isAsSelf',		'set' => 'asSelf',	'default' => false),
		'aliases' =>		array('get' => 'getAliases',	'set' => 'asA',		'default' => array()),
		'names' =>			array('get' => 'getNames',		'set' => 'named',	'default' => array()),
		'key' =>			array('get' => 'getKey',		'set' => 'keyed',	'default' => null),
	);
}
