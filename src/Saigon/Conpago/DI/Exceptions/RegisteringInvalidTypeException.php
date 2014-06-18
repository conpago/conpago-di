<?php

namespace Saigon\Conpago\DI\Exceptions;

class RegisteringInvalidTypeException extends \Exception
{
	public function __construct()
	{
		parent::__construct('Parameter is not a valid type name');
	}
}
