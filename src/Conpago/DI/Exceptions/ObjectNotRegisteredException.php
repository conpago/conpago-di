<?php

namespace Conpago\DI\Exceptions;

class ObjectNotRegisteredException extends \Exception
{
	public function __construct($name)
	{
		parent::__construct("Trying to resolve for unknown object: $name");
	}
}
