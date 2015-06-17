<?php

namespace Conpago\DI\Transformers;

use Conpago\DI\Implementation\IResolver;
use Conpago\DI\Meta;
use Conpago\DI\Resolvables\InstantiableResolvable;

class MetaTransformer extends Transformer
{
	public function transformInstantiable(InstantiableResolvable $instantiable, IResolver $container, $parameters)
	{
		return new Meta($container, $instantiable);
	}

	public static function def()
	{
		if (!self::$def)
			self::$def = new MetaTransformer();
		return self::$def;
	}

	public static function tryParseTarget($id)
	{
		if (preg_match('#^Meta<(?<target>[\w\\\]+)>$#', $id, $match))
			return (object)array('innerId' => $match['target'], 'transformer' => self::def());
		return null;
	}

	private static $def;
}
