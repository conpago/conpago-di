<?php

namespace Saigon\Conpago\DI\Transformers;

use Saigon\Conpago\DI\IFactory;
use Saigon\Conpago\DI\Implementation\Factory;
use Saigon\Conpago\DI\Implementation\IResolver;
use Saigon\Conpago\DI\Resolvables\InstantiableResolvable;

class FactoryTransformer extends Transformer
{
	/**
	 * @param InstantiableResolvable $instantiable
	 * @param IResolver $container
	 * @param $parameters
	 *
	 * @return IFactory
	 */
	public function transformInstantiable(InstantiableResolvable $instantiable, IResolver $container, $parameters)
	{
		return new Factory($container, $instantiable);
	}

	public static function def()
	{
		if (!self::$def)
			self::$def = new FactoryTransformer();
		return self::$def;
	}

	public static function tryParseTarget($id)
	{
		if (preg_match('#^Factory<(?<target>[\w\\\]+)>$#', $id, $match))
			return (object)array('innerId' => $match['target'], 'transformer' => self::def());
		return null;
	}

	private static $def;
}
