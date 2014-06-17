<?php

namespace DI\Registerers;

interface IRegisterer
{
	/**
	 * @return \DI\Builders\IBuilder
	 */
	function getBuilder();

	function getNames();
	function getAliases();
	function getKey();
}
