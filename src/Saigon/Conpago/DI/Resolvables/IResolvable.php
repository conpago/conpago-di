<?php

namespace DI\Resolvables;

use DI\Implementation\IResolver;
use DI\Transformers\ITransformer;

interface IResolvable
{
	function resolve(IResolver $container, ITransformer $transformer, $parameters);
	function transform(IResolver $container, ITransformer $transformer, $parameters);
	function isCollectable();
	function getKey();
}
