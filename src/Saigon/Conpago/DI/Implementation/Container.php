<?php

namespace DI\Implementation;

use DI\IContainer;
use DI\Transformers\DirectTransformer;

class Container implements IContainer
{
	public function __construct(IResolver $repository)
	{
		$this->repository = $repository;
	}

	public function resolve($type, $optionalParameters = null)
	{
		return $this->repository->resolveWith(str_replace(' ', '', $type), DirectTransformer::def(),
			array_slice(func_get_args(), 1));
	}

	public function resolveNamed($type, $optionalParameters = null)
	{
		return $this->repository->resolveNamedWith($type, DirectTransformer::def(),
			array_slice(func_get_args(), 1));
	}

	public function resolveAll($type, $optionalParameters = null)
	{
		return $this->repository->resolveAllWith($type, DirectTransformer::def(),
			array_slice(func_get_args(), 1));
	}

	/**
	 * @var IResolver
	 */
	private $repository;
}
