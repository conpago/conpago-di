<?php

namespace Conpago\DI\Registerers;

use Conpago\DI\Builders\TypeBuilder;
use Conpago\DI\Exceptions\RegisteringInvalidTypeException;
use Conpago\DI\ITypeRegisterer;

class TypeRegisterer extends Registerer implements ITypeRegisterer
{
	public function __construct($typeName)
	{
		$this->checkIfValidTypeName($typeName);

		$this->typeName = $typeName;
	}

	public function getBuilder()
	{
		return new TypeBuilder($this);
	}

	public function asBases()
	{
		$this->asBases = true;
		return $this;
	}

	public function asInterfaces()
	{
		$this->asInterfaces = true;
		return $this;
	}

	public function asSelf()
	{
		$this->asSelf = true;
		return $this;
	}

	public function withParams($paramList)
	{
		$this->params = func_get_args();
		return $this;
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

	public function getTypeName()
	{
		return $this->typeName;
	}

	public function isAsBases()
	{
		return $this->asBases;
	}

	public function isAsInterfaces()
	{
		return $this->asInterfaces;
	}

	public function isAsSelf()
	{
		return $this->asSelf;
	}

	public function getParams()
	{
		return $this->params;
	}

	public function isSingleInstance()
	{
		return $this->singleInstance;
	}

	public function getOnActivated()
	{
		return $this->onActivated;
	}

	private function checkIfValidTypeName($typeName)
	{
		if (!is_string($typeName))
			throw new RegisteringInvalidTypeException();
	}

	private $typeName;
	private $asBases = false;
	private $asInterfaces = false;
	private $asSelf = false;
	private $params = array();
	private $singleInstance = false;
	private $onActivated;
}
