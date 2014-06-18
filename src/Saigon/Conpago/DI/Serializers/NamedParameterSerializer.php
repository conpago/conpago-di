<?php

namespace DI\Serializers;

use DI\Parameter;

class NamedParameterSerializer implements ISerializer
{
	public function fromArray(array $configuration)
	{
		return Parameter::named($configuration['name']);
	}

	public function toArray($component)
	{
		return array(
			'name' => $component->getName(),
		);
	}
}
