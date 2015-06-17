<?php

namespace Saigon\Conpago\DI\Transformers;

use Saigon\Conpago\DI\Implementation\IResolver;
use Saigon\Conpago\DI\Resolvables\InstantiableResolvable;

class DirectTransformer extends Transformer
{
	public function transformInstantiable(InstantiableResolvable $instantiable, IResolver $container, $parameters)
	{
		return $instantiable->resolve($container, $this, $parameters);
	}

	public static function def()
	{
		if (!self::$def)
			self::$def = new DirectTransformer();
		return self::$def;
	}

	private static $def;
}
