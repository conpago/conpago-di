<?php

namespace Conpago\DI\Resolvables;

use Conpago\DI\Implementation\IResolver;
use Conpago\DI\Transformers\ITransformer;

class CompositeResolvable implements IResolvable
{
	public function __construct(IResolvable $initialComponent = null)
	{
		if ($initialComponent)
			$this->addComponent($initialComponent);
	}

	public function addComponent(IResolvable $component)
	{
		if (!$component->isCollectable())
			throw new \LogicException('Trying to collect non-collectable component');
		$this->components[] = $component;
	}

	public function resolve(IResolver $container, ITransformer $transformer, $parameters)
	{
		$result = array();
		foreach ($this->components as $component)
		{
			$resolvedObject = $component->resolve($container, $transformer, $parameters);
			if ($component->getKey())
				$result[$component->getKey()] = $resolvedObject;
			else
				$result[] = $resolvedObject;
		}
		return $result;
	}

	public function transform(IResolver $container, ITransformer $transformer, $parameters)
	{
		return $transformer->transformComposite($this, $container, $parameters);
	}

	public function isCollectable()
	{
		return false;
	}

	public function getKey()
	{
		return null;
	}

	/** @var array IResolvable */
	private $components = array();
}
