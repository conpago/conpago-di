<?php

namespace Saigon\Conpago\DI;

interface IClosureRegisterer
{
	/**
	 * Register as a specific interface
	 *
	 * @param $name
	 *
	 * @return IClosureRegisterer
	 */
	function asA($name);

	/**
	 * Register as named
	 *
	 * @param $name
	 *
	 * @return IClosureRegisterer
	 */
	function named($name);

	/**
	 * Register as keyed
	 *
	 * @param $key
	 *
	 * @return IClosureRegisterer
	 */
	function keyed($key);

	/**
	 * Register as single instance.
	 *
	 * @return IClosureRegisterer
	 */
	function singleInstance();

	/**
	 * @param $metadata
	 *
	 * @return IClosureRegisterer
	 */
	function withMetadata($metadata);

	/**
	 * @param $handler
	 *
	 * @return IClosureRegisterer
	 */
	function onActivated($handler);
}
