<?php

namespace DI\Builders;

use DI\Registerers\TypeRegisterer;
use DI\Resolvables\ParameterList;
use DI\Resolvables\TypeResolvable;

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
