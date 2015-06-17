<?php

namespace Saigon\Conpago\DI\Resolvables;

use Saigon\Conpago\DI\Implementation\IResolver;
use Saigon\Conpago\DI\Transformers\ITransformer;

interface IResolvable
{
	function resolve(IResolver $container, ITransformer $transformer, $parameters);
	function transform(IResolver $container, ITransformer $transformer, $parameters);
	function isCollectable();
	function getKey();
}
