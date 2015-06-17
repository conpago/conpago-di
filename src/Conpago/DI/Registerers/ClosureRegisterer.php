<?php

namespace Conpago\DI\Registerers;

use Conpago\DI\Builders\ClosureBuilder;
use Conpago\DI\IClosureRegisterer;
use Conpago\DI\Exceptions\RegisteringInvalidClosureException;

class ClosureRegisterer extends Registerer implements IClosureRegisterer
{
	public function __construct($closure)
	{
		$this->checkIfIsClosureOrCode($closure);

		$this->closure = $closure;
	}

	public function getBuilder()
	{
		return new ClosureBuilder($this);
	}

	public function singleInstance()
	{
		$this->singleInstance = true;
		return $this;
	}

	public function onActivated($handler)
	{
		$this->onActivated = $handler;
		return $this;
	}

	public function getClosure()
	{
		return $this->closure;
	}

	public function isSingleInstance()
	{
		return $this->singleInstance;
	}

	public function getOnActivated()
	{
		return $this->onActivated;
	}

	private function checkIfIsClosureOrCode($function)
	{
		if (is_string($function))
			return;

		if (!is_callable($function) || !is_object($function))
			throw new RegisteringInvalidClosureException;

		$rf = new \ReflectionFunction($function);
		if (!$rf->isClosure())
			throw new RegisteringInvalidClosureException;
	}

	private $closure;
	private $singleInstance = false;
	private $onActivated;
}
