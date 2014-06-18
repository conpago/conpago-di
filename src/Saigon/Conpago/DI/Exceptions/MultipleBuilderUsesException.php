<?php

namespace DI\Exceptions;

class MultipleBuilderUsesException extends \Exception
{
	public function __construct()
	{
		parent::__construct('Multiple uses of the same container builder are not allowed');
	}
}
