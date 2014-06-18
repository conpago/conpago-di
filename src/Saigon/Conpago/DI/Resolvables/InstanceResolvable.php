<?php

namespace DI\Resolvables;

class InstanceResolvable extends InstantiableResolvable
{
	public function __construct($instance, $key, $metadata)
	{
		parent::__construct(true, $key, $metadata, $instance);
	}
}
