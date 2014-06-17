<?php

namespace DI\Serializers;

use DI\Parameter;

class DefaultParameterSerializer implements ISerializer
{
	public function fromArray(array $configuration)
	{
		return Parameter::def();
	}

	public function toArray($component)
	{
		return array();
	}
}
