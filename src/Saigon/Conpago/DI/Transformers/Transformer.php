<?php

namespace Saigon\Conpago\DI\Transformers;

use Saigon\Conpago\DI\Implementation\IResolver;
use Saigon\Conpago\DI\Resolvables\AliasResolvable;
use Saigon\Conpago\DI\Resolvables\CompositeResolvable;

abstract class Transformer implements ITransformer
{
	public function transformAlias(AliasResolvable $alias, IResolver $container, $parameters)
	{
		return $alias->resolve($container, $this, $parameters);
	}

	public function transformComposite(CompositeResolvable $composite, IResolver $container, $parameters)
	{
		return $composite->resolve($container, $this, $parameters);
	}
}
