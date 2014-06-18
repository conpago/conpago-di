<?php

namespace DI\Parameters;

use DI\Implementation\IResolver;

interface IParameter
{
	function resolve(IResolver $container);
	function getTargetName();
	function isArray();
}
