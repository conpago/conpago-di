<?php

namespace Conpago\DI\Builders;

use Conpago\DI\Registerers\TypeRegisterer;
use Conpago\DI\Resolvables\ParameterList;
use Conpago\DI\Resolvables\TypeResolvable;

class TypeBuilder extends TypeableBuilder
{
	public function __construct(TypeRegisterer $registerer)
	{
		parent::__construct('Type', $registerer->getTypeName(), $registerer);
	}

	protected function buildDefinition()
	{
		return array($this->name => new TypeResolvable(
			$this->typeName,
			new ParameterList($this->typeName, $this->registerer->getParams()),
			$this->registerer->isSingleInstance(),
			$this->registerer->getKey(),
			$this->registerer->getMetadata(),
			$this->registerer->getOnActivated()));
	}
}
