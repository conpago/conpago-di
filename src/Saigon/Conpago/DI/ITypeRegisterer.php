<?php

namespace DI;

interface ITypeRegisterer
{
	/**
	 * @param $name
	 *
	 * @return ITypeRegisterer
	 */
	function asA($name);

	/**
	 * @return ITypeRegisterer
	 */
	function asBases();

	/**
	 * @return ITypeRegisterer
	 */
	function asInterfaces();

	/**
	 * @return ITypeRegisterer
	 */
	function asSelf();

	/**
	 * @param $name
	 *
	 * @return ITypeRegisterer
	 */
	function named($name);

	/**
	 * @param $key
	 *
	 * @return ITypeRegisterer
	 */
	function keyed($key);

	/**
	 * @param $paramList
	 *
	 * @return ITypeRegisterer
	 */
	function withParams($paramList);

	/**
	 * @param $metadata
	 *
	 * @return ITypeRegisterer
	 */
	function withMetadata($metadata);

	/**
	 * @return ITypeRegisterer
	 */
	function singleInstance();

	/**
	 * @param $handler
	 *
	 * @return ITypeRegisterer
	 */
	function onActivated($handler);
}
