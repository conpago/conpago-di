<?php

namespace Saigon\Conpago\DI\Registerers;

interface IRegisterer
{
	/**
	 * @return \Saigon\Conpago\DI\Builders\IBuilder
	 */
	function getBuilder();

	function getNames();
	function getAliases();
	function getKey();
}
