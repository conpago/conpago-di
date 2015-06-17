<?php

namespace Conpago\DI\Transformers;

use Conpago\DI\Implementation\IResolver;
use Conpago\DI\Resolvables\AliasResolvable;
use Conpago\DI\Resolvables\CompositeResolvable;
use Conpago\DI\Resolvables\InstantiableResolvable;

interface ITransformer
{
	function transformAlias(AliasResolvable $alias, IResolver $container, $parameters);
	function transformInstantiable(InstantiableResolvable $instantiable, IResolver $container, $parameters);
	function transformComposite(CompositeResolvable $composite, IResolver $container, $parameters);
}
