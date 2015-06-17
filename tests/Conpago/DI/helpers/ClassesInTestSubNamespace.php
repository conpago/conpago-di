<?php

namespace Test\Sub;

class ClassA {}

class ClassN1
{
	/**
	 * @inject \Test\ClassA $a
	 */
	public function __construct($a)
	{ $this->a = $a; }
}
