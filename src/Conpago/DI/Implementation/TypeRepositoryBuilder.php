<?php

namespace Conpago\DI\Implementation;

use Conpago\DI\Builders\IBuilder;
use Conpago\DI\Resolvables\CompositeResolvable;
use Conpago\DI\Resolvables\IResolvable;

class TypeRepositoryBuilder
{
	public function __construct(array $registerers)
	{
		$this->registerers = $registerers;
	}

	public function build()
	{
		$this->registry = array();
		foreach ($this->getBuilders() as $builder)
			$this->addFromBuilder($builder);
		return $this->registry;
	}

	private function getBuilders()
	{
		$builders = array();
		foreach ($this->registerers as $registerer)
			$builders[] = $registerer->getBuilder();
		return $builders;
	}

	private function addFromBuilder(IBuilder $builder)
	{
		$components = $builder->build();
		foreach ($components as $id => $component)
			$this->addComponent($id, $component);
	}

	private function addComponent($id, IResolvable $component)
	{
		if ($component->isCollectable() && isset($this->registry[$id]))
			$this->addCollectable($id, $component);
		$this->registry[$id] = $component;
	}

	private function addCollectable($id, IResolvable $component)
	{
		$composite = $this->getComposite($id);
		$composite->addComponent($component);
	}

	private function getComposite($id)
	{
		$containerName = 'Container: ' . $id;
		if (isset($this->registry[$containerName]))
			return $this->registry[$containerName];

		return $this->makeComposite($id);
	}

	private function makeComposite($id)
	{
		$composite = new CompositeResolvable($this->registry[$id]);
		$this->registry['Container: ' . $id] = $composite;

		return $composite;
	}

	private $registry;
	private $registerers;
}
