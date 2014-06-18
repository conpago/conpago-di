<?php

namespace Saigon\Conpago\DI\Exceptions;

class RegisteringInvalidClosureException extends \Exception
{
	public function __construct()
	{
		parent::__construct('Parameter passed is not a closure');
	}
}
