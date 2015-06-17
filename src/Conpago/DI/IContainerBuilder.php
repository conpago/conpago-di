<?php

namespace Conpago\DI;

interface IContainerBuilder
{
	/**
	 * @param $instance
	 *
	 * @return IInstanceRegisterer
	 */
	function registerInstance($instance);

	/**
	 * @param $className
	 *
	 * @return ITypeRegisterer
	 */
	function registerType($className);

	/**
	 * @param $function
	 *
	 * @return IClosureRegisterer
	 */
	function register($function);

	/**
	 * @return IContainer
	 */
	function build();

	function getConfiguration();
}
