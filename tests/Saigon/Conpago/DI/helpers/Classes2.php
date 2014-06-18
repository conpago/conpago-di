<?php

interface InterfaceA1 {}
interface InterfaceA2 {}
class ClassA implements InterfaceA1, InterfaceA2 {}
interface InterfaceB1 {}
interface InterfaceB2 {}
class ClassB extends ClassA implements InterfaceB1, InterfaceB2{}
class ClassC extends ClassB{}

class ClassD
{
	public function __construct()
	{ self::$instances++; }
	public static $instances;
}

class ClassE
{
	public function __construct($value = "default")
	{ $this->value = $value; }
}

class ClassE2 extends ClassE
{
	public function __construct($value)
	{ parent::__construct($value); }
}

class ClassE3 extends ClassE
{
	public function __construct($value1 = 'e', $value2 = 'e3')
	{ parent::__construct($value1); $this->value2 = $value2; }
}

class ClassF1
{
	public function __construct(\Delayed\ClassB $a)
	{ $this->a = $a; }
}

class ClassF2
{
	/**
	 * @inject Lazy<\Delayed\ClassB> $a
	 */
	public function __construct($a)
	{ $this->a = $a; }
}

class ClassM1
{
	/**
	 * @inject Meta<ClassA> $a
	 */
	public function __construct($a)
	{ $this->a = $a; }
}

class ClassM2
{
	/**
	 * @inject Meta<ClassA> $a
	 */
	public function __construct(array $a)
	{ $this->a = $a; }
}

class ClassM3
{
	/**
	 * @inject Meta<InterfaceA1> $a
	 */
	public function __construct(array $a)
	{ $this->a = $a; }
}

class ClassN1
{
	public function __construct(\Test\ClassA $a)
	{ $this->a = $a; }
}

class ClassN2
{
	/**
	 * @inject Test\ClassA $a
	 */
	public function __construct($a)
	{ $this->a = $a; }
}

class ClassN3
{
	/**
	 * @inject \Test\ClassA $a
	 */
	public function __construct($a)
	{ $this->a = $a; }
}

class ClassN4
{
	public function __construct(\Test\Sub\ClassA $a)
	{ $this->a = $a; }
}

class ClassN10
{
	/**
	 * @inject Test\ClassA $a
	 */
	public function __construct(array $a)
	{ $this->a = $a; }
}

class ClassN11
{
	/**
	 * @inject \Test\ClassA $a
	 */
	public function __construct(array $a)
	{ $this->a = $a; }
}

class ClassN12
{
	/**
	 * @inject Test\Sub\ClassA $a
	 */
	public function __construct(array $a)
	{ $this->a = $a; }
}

class ClassN13
{
	/**
	 * @inject \Test\Sub\ClassA $a
	 */
	public function __construct(array $a)
	{ $this->a = $a; }
}

class ClassN21
{
	/**
	 * @inject Factory < \ Test \ ClassA > $a
	 */
	public function __construct($a)
	{ $this->a = $a; }
}

class ClassN22
{
	/**
	 * @inject Factory<Test\ClassA> $a
	 */
	public function __construct($a)
	{ $this->a = $a; }
}

class ClassN25
{
	/**
	 * @inject Factory<Test\ClassA> $a
	 */
	public function __construct(array $a)
	{ $this->a = $a; }
}

class ClassN31
{
	/**
	 * @inject Lazy < \ Test \ ClassA > $a
	 */
	public function __construct($a)
	{ $this->a = $a; }
}

class ClassN32
{
	/**
	 * @inject Lazy<Test\ClassA> $a
	 */
	public function __construct($a)
	{ $this->a = $a; }
}

class ClassN35
{
	/**
	 * @inject Lazy<Test\ClassA> $a
	 */
	public function __construct(array $a)
	{ $this->a = $a; }
}
