<?php

namespace Saigon\Conpago\DI\Builders;

use Saigon\Conpago\DI\Registerers\ClosureRegisterer;
use Saigon\Conpago\DI\Resolvables\ClosureResolvable;

class ClosureBuilder extends Builder
{
	public function __construct(ClosureRegisterer $registerer)
	{
		parent::__construct('Closure', null, $registerer);
	}

	public function build()
	{
		return array_merge(
			$this->buildDefinition(),
			$this->buildAliases(),
			$this->buildNames()
		);
	}

	private function buildDefinition()
	{
		return array(
			$this->name => new ClosureResolvable(
				$this->registerer->getClosure(),
				$this->registerer->isSingleInstance(),
				$this->registerer->getKey(),
				$this->registerer->getMetadata(),
				$this->registerer->getOnActivated())
		);
	}
}
