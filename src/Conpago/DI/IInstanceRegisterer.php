<?php

namespace Conpago\DI;

interface IInstanceRegisterer
{
	/**
	 * @param $name
	 *
	 * @return IInstanceRegisterer
	 */
	function asA($name);

	/**
	 * @return IInstanceRegisterer
	 */
	function asSelf();

	/**
	 * @return IInstanceRegisterer
	 */
	function asBases();

	/**
	 * @return IInstanceRegisterer
	 */
	function asInterfaces();

	/**
	 * @param $name
	 *
	 * @return IInstanceRegisterer
	 */
	function named($name);

	/**
	 * @param $key
	 *
	 * @return IInstanceRegisterer
	 */
	function keyed($key);

	/**
	 * @param $metadata
	 *
	 * @return IInstanceRegisterer
	 */
	function withMetadata($metadata);
}
