<?php

namespace DI\Builders;

use DI\Registerers\IRegisterer;
use DI\Resolvables\AliasResolvable;

abstract class Builder implements IBuilder
{
	protected function __construct($type, $name, IRegisterer $registerer)
	{
		$this->registerer = $registerer;
		$this->name = "{$type}: {$name}, " . (++self::$instanceNumber);
	}

	public function getNames()
	{
		if (!$this->hasNames())
			return array($this->name);

		$result = array();
		foreach ($this->registerer->getNames() as $name)
			$result[] = $this->name . " ($name)";
		return $result;
	}

	abstract function build();

	protected function getName()
	{
		return $this->name;
	}

	protected function getKey()
	{
		return $this->registerer->getKey();
	}

	protected function buildNames()
	{
		$result = array();
		foreach ($this->registerer->getNames() as $name)
			$result['Name: ' . $name] = $this->makeAlias();
		return $result;
	}

	protected function buildAliases()
	{
		$result = array();
		foreach ($this->registerer->getAliases() as $asa)
			$result[$this->resolveNamespace($asa)] = $this->makeAlias();
		return $result;
	}

	protected function resolveNamespace($alias)
	{
		return ltrim($alias, '\\');
	}

	protected function buildKeys()
	{
		if (!$this->hasKey())
			return array();

		return array('Key: ' . $this->registerer->getKey() => $this->makeAlias());
	}

	protected function makeAlias()
	{
		return new AliasResolvable($this->name, $this->registerer->getKey());
	}

	protected function hasAliases()
	{
		return $this->registerer->getAliases();
	}

	protected function hasNames()
	{
		return $this->registerer->getNames();
	}

	protected function hasKey()
	{
		return $this->registerer->getKey();
	}

	protected $registerer;
	protected $name;

	private static $instanceNumber = 0;
}
