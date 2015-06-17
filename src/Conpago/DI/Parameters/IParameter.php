<?php

namespace Conpago\DI\Parameters;

use Conpago\DI\Implementation\IResolver;

interface IParameter
{
	function resolve(IResolver $container);
	function getTargetName();
	function isArray();
}
