<?php

namespace Conpago\DI\Transformers;

use Conpago\DI\Implementation\IResolver;
use Conpago\DI\Resolvables\AliasResolvable;
use Conpago\DI\Resolvables\CompositeResolvable;

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
