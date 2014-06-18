<?php

namespace Saigon\Conpago\DI;

require_once 'DITestCase.php';

class RegisterClosureAsSingletonTest extends DITestCase
{
	public function test_RegisterFunctionAsSingleton()
	{
		$this->register(function() { return new \ClassA; })->asA('ClassA')->singleInstance();
		$classA = $this->resolve('ClassA');
		$this->assertSame($classA, $this->resolve('ClassA'));
	}

	public function test_RegisterFunctionAsSingletonNamed()
	{
		$this->register(function() { return new \ClassA; })->named('test')->asA('ClassA')->singleInstance();
		$classA = $this->resolve('ClassA');
		$this->assertSame($classA, $this->resolve('ClassA'));
		$this->assertSame($classA, $this->resolveNamed('test'));
	}

	public function test_RegisterAsCodeSingleton()
	{
		$this->register('return new \\ClassD')->asA('ClassD')->singleInstance();
		$d = $this->resolve('ClassD');
		$this->assertInstanceOf('ClassD', $d);
		$this->assertEquals(1, \ClassD::$instances);
		$d = $this->resolve('ClassD');
		$this->assertInstanceOf('ClassD', $d);
		$this->assertEquals(1, \ClassD::$instances);
	}
}
