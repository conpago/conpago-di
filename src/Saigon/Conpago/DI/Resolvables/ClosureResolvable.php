<?php

namespace DI\Resolvables;

use DI\IContainer;
use DI\Implementation\IResolver;
use DI\Transformers\ITransformer;

class ClosureResolvable extends InstantiableResolvable
{
	public function __construct($closure, $single, $key, $metadata, $onActivated)
	{
		parent::__construct($single, $key, $metadata);

		$this->closure = $closure;
		$this->onActivated = $onActivated;
	}

	protected function resolveObject(IResolver $resolver, ITransformer $transformer, $parameters)
	{
		if (is_string($this->closure))
			$result = self::resolveFromCode($resolver->getContainer(), $this->closure);
		else
			$result = $this->resolveFromClosure($resolver, $parameters);
		$this->callOnActivated($result, $resolver);
		return $result;
	}

	private static function resolveFromCode(IContainer $container, $code)
	{
		return eval($code . ';');
	}

	private function resolveFromClosure(IResolver $resolver, $paramValues)
	{
		$args = array_merge(array($resolver->getContainer()), $paramValues);
		return call_user_func_array($this->closure, $args);
	}

	private function callOnActivated($object, IResolver $resolver)
	{
		if (!$this->onActivated)
			return;
		$handler = $this->onActivated;
		$handler($object, $resolver->getContainer());
	}

	private $closure;
	private $onActivated;
}
