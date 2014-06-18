<?php

namespace Saigon\Conpago\DI\Serializers;

use Saigon\Conpago\DI\Parameter;

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
