<?php

namespace Saigon\Conpago\DI\Transformers;

use Saigon\Conpago\DI\Implementation\IResolver;
use Saigon\Conpago\DI\Resolvables\AliasResolvable;
use Saigon\Conpago\DI\Resolvables\CompositeResolvable;
use Saigon\Conpago\DI\Resolvables\InstantiableResolvable;

interface ITransformer
{
	function transformAlias(AliasResolvable $alias, IResolver $container, $parameters);
	function transformInstantiable(InstantiableResolvable $instantiable, IResolver $container, $parameters);
	function transformComposite(CompositeResolvable $composite, IResolver $container, $parameters);
}
