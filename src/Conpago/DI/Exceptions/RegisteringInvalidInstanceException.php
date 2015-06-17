<?php

namespace Conpago\DI\Exceptions;

class RegisteringInvalidInstanceException extends \Exception
{
	public function __construct()
	{
		parent::__construct('Parameter passed is not an object');
	}
}
