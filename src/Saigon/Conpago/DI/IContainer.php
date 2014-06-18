<?php

namespace DI;

interface IContainer
{
	function resolve($name, $optionalParams = null);
	function resolveNamed($name, $optionalParams = null);
	function resolveAll($name, $optionalParams = null);
}
