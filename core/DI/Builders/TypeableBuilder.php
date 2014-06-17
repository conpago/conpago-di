<?php

namespace DI\Builders;

use DI\Registerers\IRegisterer;

abstract class TypeableBuilder extends Builder
{
	public function __construct($elementType, $typeName, IRegisterer $registerer)
	{
		$this->typeName = $typeName;

		parent::__construct($elementType, $this->typeName, $registerer);
	}

	public function build()
	{
		return array_merge(
			$this->buildDefinition(),
			$this->buildType(),
			$this->buildAliases(),
			$this->buildBases(),
			$this->buildInterfaces(),
			$this->buildNames()
		);
	}

	abstract protected function buildDefinition();

	private function buildType()
	{
		if ($this->isRegisteredAsSelf())
			return array($this->resolveNamespace($this->typeName) => $this->makeAlias());

		return array();
	}

	private function buildBases()
	{
		$result = array();
		if (!$this->registerer->isAsBases())
			return $result;

		$rc = new \ReflectionClass($this->typeName);
		while ($rc = $rc->getParentClass())
			$result[$this->resolveNamespace($rc->getName())] = $this->makeAlias();

		return $result;
	}

	private function buildInterfaces()
	{
		$result = array();
		if (!$this->registerer->isAsInterfaces())
			return $result;

		$rc = new \ReflectionClass($this->typeName);
		foreach ($rc->getInterfaceNames() as $base)
			$result[$this->resolveNamespace($base)] = $this->makeAlias();

		return $result;
	}

	private function isRegisteredAsSelf()
	{
		return (!$this->hasAliases() && !$this->hasNames() && !$this->hasKey())
			|| $this->registerer->isAsSelf();
	}

	protected function hasAliases()
	{
		return parent::hasAliases() || $this->registerer->isAsBases() || $this->registerer->isAsInterfaces();
	}

	protected $typeName;
}
