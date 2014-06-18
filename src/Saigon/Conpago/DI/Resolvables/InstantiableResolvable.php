<?php

namespace DI\Resolvables;

use DI\Implementation\IResolver;
use DI\Transformers\ITransformer;

abstract class InstantiableResolvable implements IResolvable
{
	public function __construct($single, $key, $metadata, $instance = null)
	{
		$this->single = $single;
		$this->key = $key;
		$this->instance = $instance;
		$this->metadata = $metadata;
	}

	public function resolve(IResolver $container, ITransformer $transformer, $parameters)
	{
		if ($this->instance)
			return $this->instance;

		$result = $this->resolveObject($container, $transformer, $parameters);

		if ($this->single)
			$this->instance = $result;

		return $result;
	}

	public function transform(IResolver $container, ITransformer $transformer, $parameters)
	{
		return $transformer->transformInstantiable($this, $container, $parameters);
	}

	public function isCollectable()
	{
		return false;
	}

	public function getKey()
	{
		return $this->key;
	}

	public function getMetadata()
	{
		return $this->metadata;
	}

	protected function resolveObject(IResolver $container, ITransformer $transformer, $parameters)
	{
		throw new \LogicException('Cannot not resolve object of this type');
	}

	private $single;
	private $instance;
	private $key;
	private $metadata;
}
