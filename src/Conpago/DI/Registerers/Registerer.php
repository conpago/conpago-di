<?php

namespace Conpago\DI\Registerers;

abstract class Registerer implements IRegisterer
{
	public function asA($name)
	{
		$this->asa[] = $name;
		return $this;
	}

	public function named($name)
	{
		$this->names[] = $name;
		return $this;
	}

	public function keyed($key)
	{
		$this->key = $key;
		return $this;
	}

	public function withMetadata($metadata)
	{
		$this->metadata = $metadata;
		return $this;
	}

	public function getAliases()
	{
		return $this->asa;
	}

	public function getNames()
	{
		return $this->names;
	}

	public function getKey()
	{
		return $this->key;
	}

	public function getMetadata()
	{
		return $this->metadata;
	}

	private $asa = array();
	private $names = array();
	private $key;
	private $metadata;
}
