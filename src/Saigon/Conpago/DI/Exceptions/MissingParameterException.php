<?php

namespace Saigon\Conpago\DI\Exceptions;

class MissingParameterException extends \Exception
{
	public function __construct()
	{
		parent::__construct('Too few parameters passed');
	}
}
