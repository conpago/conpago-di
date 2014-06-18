<?php

namespace DI\Serializers;

interface ISerializer
{
	function toArray($component);
	function fromArray(array $configuration);
}
