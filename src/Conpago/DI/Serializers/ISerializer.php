<?php

namespace Conpago\DI\Serializers;

interface ISerializer
{
	function toArray($component);
	function fromArray(array $configuration);
}
