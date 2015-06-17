<?php

namespace Saigon\Conpago\DI\Transformers;

use Saigon\Conpago\DI\Implementation\IResolver;
use Saigon\Conpago\DI\Lazy;
use Saigon\Conpago\DI\Resolvables\InstantiableResolvable;

class LazyTransformer extends Transformer
{
	public function transformInstantiable(InstantiableResolvable $instantiable, IResolver $container, $parameters)
	{
		return new Lazy($container, $instantiable);
	}

	public static function def()
	{
		if (!self::$def)
			self::$def = new LazyTransformer();
		return self::$def;
	}

	public static function tryParseTarget($id)
	{
		if (preg_match('#^Lazy<(?<target>[\w\\\]+)>$#', $id, $match))
			return (object)array('innerId' => $match['target'], 'transformer' => self::def());
		return null;
	}

	private static $def;
}
