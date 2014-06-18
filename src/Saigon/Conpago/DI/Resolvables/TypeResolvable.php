<?php

namespace Saigon\Conpago\DI\Resolvables;

use Saigon\Conpago\DI\Implementation\IResolver;
use Saigon\Conpago\DI\Transformers\ITransformer;

class TypeResolvable extends InstantiableResolvable
{
	public function __construct($class, $params, $single, $key, $metadata, $onActivated)
	{
		parent::__construct($single, $key, $metadata);

		$this->class = $class;
		$this->params = $params;
		$this->onActivated = $onActivated;
	}

	protected function resolveObject(IResolver $resolver, ITransformer $transformer, $parameters)
	{
		$rc = new \ReflectionClass($this->class);
		$result = $rc->newInstanceArgs($this->resolveParameters($resolver, $parameters));
		$this->callOnActivated($result, $resolver);
		return $result;
	}

	private function resolveParameters(IResolver $resolver, $parameters)
	{
		return $this->params->resolve($resolver, $parameters);
	}

	private function callOnActivated($object, IResolver $resolver)
	{
		if (!$this->onActivated)
			return;
		$handler = $this->onActivated;
		$handler($object, $resolver->getContainer());
	}

	private $class;
	private $params;
	private $onActivated;
}
