<?php

namespace Conpago\DI\Transformers;

use Conpago\DI\IFactory;
use Conpago\DI\Implementation\Factory;
use Conpago\DI\Implementation\IResolver;
use Conpago\DI\Resolvables\InstantiableResolvable;

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
