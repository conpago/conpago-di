<?php

namespace DI\Transformers;

use DI\Implementation\IResolver;
use DI\Resolvables\AliasResolvable;
use DI\Resolvables\CompositeResolvable;

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
