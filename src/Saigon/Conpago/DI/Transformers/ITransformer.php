<?php

namespace DI\Transformers;

use DI\Implementation\IResolver;
use DI\Resolvables\AliasResolvable;
use DI\Resolvables\CompositeResolvable;
use DI\Resolvables\InstantiableResolvable;

interface ITransformer
{
	function transformAlias(AliasResolvable $alias, IResolver $container, $parameters);
	function transformInstantiable(InstantiableResolvable $instantiable, IResolver $container, $parameters);
	function transformComposite(CompositeResolvable $composite, IResolver $container, $parameters);
}
