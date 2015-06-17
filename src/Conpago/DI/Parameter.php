<?php

namespace Saigon\Conpago\DI;

abstract class Parameter
{
	public static function def()
	{
		if (!self::$def)
			self::$def = new DefaultParameter();
		return self::$def;
	}

	public static function missing()
	{
		if (!self::$missing)
			self::$missing = new MissingParameter();
		return self::$missing;
	}

	public static function named($name)
	{
		return new NamedParameter($name);
	}

	private static $def;
	private static $missing;
}

class DefaultParameter extends Parameter {}

class MissingParameter extends Parameter {}

class NamedParameter extends Parameter
{
	public function __construct($name)
	{
		$this->name = $name;
	}

	public function getName()
	{
		return $this->name;
	}

	private $name;
}
