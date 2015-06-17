<?php

namespace Saigon\Conpago\DI\Parameters;

use Saigon\Conpago\DI\Implementation\IResolver;

interface IParameter
{
	function resolve(IResolver $container);
	function getTargetName();
	function isArray();
}
