<?php

namespace Conpago\DI\Registerers;

interface IRegisterer
{
	/**
	 * @return \Conpago\DI\Builders\IBuilder
	 */
	function getBuilder();

	function getNames();
	function getAliases();
	function getKey();
}
