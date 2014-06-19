<?php

namespace Saigon\Conpago\DI\Implementation;

use Saigon\Conpago\DI\Resolvables\InstantiableResolvable;
use Saigon\Conpago\DI\Resolvables\IResolvable;

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

	/**
	 * @return IResolvable
	 */
	protected function getTarget()
	{
		return $this->target;
	}

	/**
	 * @return IResolver
	 */
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
