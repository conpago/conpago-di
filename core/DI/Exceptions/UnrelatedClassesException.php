<?php

namespace DI\Exceptions;

class UnrelatedClassesException extends \Exception
{
	public function __construct($class1, $class2)
	{
		parent::__construct("$class1 is not an instance of $class2");
	}
}
