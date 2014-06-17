<?php

namespace DI\Implementation;

use DI\Resolvables\InstantiableResolvable;
use DI\Resolvables\IResolvable;

abstract class Intermediate implements IIntermediate
{
	public function __construct(IResolver $container, InstantiableResolvable $target)
	{
		$this->container = $container;
		$this->target = $target;
	}

	public function getTargetName()
	{
		return $this->target->getTargetName();
	}

	protected function getTarget()
	{
		return $this->target;
	}

	protected function getContainer()
	{
		return $this->container;
	}

	/**
	 * @var IResolver
	 */
	private $container;

	/**
	 * @var IResolvable
	 */
	private $target;
}
