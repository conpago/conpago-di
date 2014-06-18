<?php

namespace DI\Registerers;

use DI\Builders\InstanceBuilder;
use DI\Exceptions\RegisteringInvalidInstanceException;
use DI\Exceptions\UnrelatedClassesException;
use DI\IInstanceRegisterer;

class InstanceRegisterer extends Registerer implements IInstanceRegisterer
{
	public function __construct($instance)
	{
		$this->checkIfIsInstance($instance);

		$this->instance = $instance;
	}

	public function getBuilder()
	{
		return new InstanceBuilder($this);
	}

	public function asBases()
	{
		$this->asBases = true;
		return $this;
	}

	public function asA($name)
	{
		if (!is_subclass_of($this->instance, $name) && get_class($this->instance) != $name)
			throw new UnrelatedClassesException('Object', $name);

		return parent::asA($name);
	}

	public function asSelf()
	{
		$this->asSelf = true;
		return $this;
	}

	public function asInterfaces()
	{
		$this->asInterfaces = true;
		return $this;
	}

	public function getInstance()
	{
		return $this->instance;
	}

	public function isAsBases()
	{
		return $this->asBases;
	}

	public function isAsSelf()
	{
		return $this->asSelf;
	}

	public function isAsInterfaces()
	{
		return $this->asInterfaces;
	}

	private function checkIfIsInstance($instance)
	{
		if (!is_object($instance))
			throw new RegisteringInvalidInstanceException;
	}

	private $instance;
	private $asBases = false;
	private $asSelf = false;
	private $asInterfaces = false;
}
