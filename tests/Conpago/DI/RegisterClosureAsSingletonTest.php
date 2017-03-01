<?php

namespace Conpago\DI;

use ClassA;
use ClassD;

require_once 'DITestCase.php';

class RegisterClosureAsSingletonTest extends DITestCase
{
	public function test_RegisterFunctionAsSingleton()
	{
		$this->register(function() { return new ClassA; })->asA(ClassA::class)->singleInstance();
		$classA = $this->resolve(ClassA::class);
		$this->assertSame($classA, $this->resolve(ClassA::class));
	}

	public function test_RegisterFunctionAsSingletonNamed()
	{
		$this->register(function() { return new ClassA; })->named('test')->asA(ClassA::class)->singleInstance();
		$classA = $this->resolve(ClassA::class);
		$this->assertSame($classA, $this->resolve(ClassA::class));
		$this->assertSame($classA, $this->resolveNamed('test'));
	}

	public function test_RegisterAsCodeSingleton()
	{
		$this->register('return new \\ClassD')->asA(ClassD::class)->singleInstance();
		$d = $this->resolve(ClassD::class);
		$this->assertInstanceOf(ClassD::class, $d);
		$this->assertEquals(1, ClassD::$instances);
		$d = $this->resolve(ClassD::class);
		$this->assertInstanceOf(ClassD::class, $d);
		$this->assertEquals(1, ClassD::$instances);
	}
}
