<?php

namespace Test;

class ClassA {}

class ClassM1
{
	/**
	 * @inject Meta<\ClassA> $a
	 */
	public function __construct($a)
	{ $this->a = $a; }
}

class ClassM2
{
	/**
	 * @inject Meta<Sub\ClassA> $a
	 */
	public function __construct($a)
	{ $this->a = $a; }
}

class ClassN1
{
	/**
	 * @inject \ClassA $a
	 */
	public function __construct($a)
	{ $this->a = $a; }
}

class ClassN2
{
	/**
	 * @inject \ Test \ ClassA $a
	 */
	public function __construct($a)
	{ $this->a = $a; }
}

class ClassN3
{
	/**
	 * @inject ClassA $a
	 */
	public function __construct($a)
	{ $this->a = $a; }
}

class ClassN4
{
	/**
	 * @inject Test\ClassA $a
	 */
	public function __construct($a)
	{ $this->a = $a; }
}

class ClassN5
{
	/**
	 * @inject \Test\Sub\ClassA $a
	 */
	public function __construct($a)
	{ $this->a = $a; }
}

class ClassN6
{
	/**
	 * @inject Sub\ClassA $a
	 */
	public function __construct($a)
	{ $this->a = $a; }
}

class ClassN7
{
	public function __construct(ClassA $a)
	{ $this->a = $a; }
}

class ClassN8
{
	public function __construct(\Test\ClassA $a)
	{ $this->a = $a; }
}

class ClassN10
{
	/**
	 * @inject \Test\Sub\ClassA $a
	 */
	public function __construct(array $a)
	{ $this->a = $a; }
}

class ClassN11
{
	/**
	 * @inject Sub\ClassA $a
	 */
	public function __construct(array $a)
	{ $this->a = $a; }
}

class ClassN21
{
	/**
	 * @inject Factory<\ClassA> $a
	 */
	public function __construct($a)
	{ $this->a = $a; }
}

class ClassN22
{
	/**
	 * @inject Factory<Sub\ClassA> $a
	 */
	public function __construct($a)
	{ $this->a = $a; }
}

class ClassN25
{
	/**
	 * @inject Factory<\ClassA> $a
	 */
	public function __construct(array $a)
	{ $this->a = $a; }
}

class ClassN26
{
	/**
	 * @inject Factory<Sub\ClassA> $a
	 */
	public function __construct(array $a)
	{ $this->a = $a; }
}

class ClassN31
{
	/**
	 * @inject Lazy<\ClassA> $a
	 */
	public function __construct($a)
	{ $this->a = $a; }
}

class ClassN32
{
	/**
	 * @inject Lazy<Sub\ClassA> $a
	 */
	public function __construct($a)
	{ $this->a = $a; }
}

class ClassN35
{
	/**
	 * @inject Lazy<\ClassA> $a
	 */
	public function __construct(array $a)
	{ $this->a = $a; }
}

class ClassN36
{
	/**
	 * @inject Lazy<Sub\ClassA> $a
	 */
	public function __construct(array $a)
	{ $this->a = $a; }
}
