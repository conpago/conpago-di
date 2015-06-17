<?php

namespace Conpago\DI\Builders;

use Conpago\DI\Registerers\InstanceRegisterer;
use Conpago\DI\Resolvables\InstanceResolvable;

class InstanceBuilder extends TypeableBuilder
{
	public function __construct(InstanceRegisterer $registerer)
	{
		parent::__construct('Instance', get_class($registerer->getInstance()), $registerer);
	}

	protected function buildDefinition()
	{
		return array($this->name => new InstanceResolvable(
			$this->registerer->getInstance(),
			$this->registerer->getKey(),
			$this->registerer->getMetadata()));
	}
}
