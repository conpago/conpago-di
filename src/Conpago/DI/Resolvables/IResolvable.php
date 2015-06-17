<?php

namespace Conpago\DI\Resolvables;

use Conpago\DI\Implementation\IResolver;
use Conpago\DI\Transformers\ITransformer;

interface IResolvable
{
	function resolve(IResolver $container, ITransformer $transformer, $parameters);
	function transform(IResolver $container, ITransformer $transformer, $parameters);
	function isCollectable();
	function getKey();
}
