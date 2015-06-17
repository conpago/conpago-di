<?php

namespace Conpago\DI\Resolvables;

use Conpago\DI\Implementation\IResolver;
use Conpago\DI\Transformers\ITransformer;

class AliasResolvable implements IResolvable
{
	public function __construct($target, $key)
	{
		$this->target = $target;
		$this->key = $key;
	}

	public function resolve(IResolver $container, ITransformer $transformer, $parameters)
	{
		return $container->resolveWith($this->target, $transformer, $parameters);
	}

	public function transform(IResolver $container, ITransformer $transformer, $parameters)
	{
		return $transformer->transformAlias($this, $container, $parameters);
	}

	public function isCollectable()
	{
		return true;
	}

	public function getKey()
	{
		return $this->key;
	}

	private $target;
	private $key;
}
