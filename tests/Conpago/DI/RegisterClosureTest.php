<?php

namespace Conpago\DI;

require_once 'DITestCase.php';

class RegisterClosureTest extends DITestCase
{
	public function test_Register_ResolveSelf()
	{
		$this->builder->register(function() { return new \ClassD; })->asA('ClassD');
		$this->assertEquals(0, \ClassD::$instances);
		$container = $this->builder->build();
		$this->assertEquals(0, \ClassD::$instances);
		$this->assertInstanceOf('ClassD', $container->resolve('ClassD'));
		$this->assertEquals(1, \ClassD::$instances);
	}

	/**
	 * @expectedException \Conpago\DI\Exceptions\UnrelatedClassesException
	 */
	public function test_Register_ResolveUnrelated_Fail()
	{
		$this->builder->register(function() { return new \ClassD; })->asA('ClassA');
		$this->assertInstOf('ClassD', 'ClassA');
	}

	public function test_Register_ResolveWithContainer()
	{
		$this->builder->registerType('ClassB');
		$this->builder->register(function(IContainer $c) { return $c->resolve('ClassB'); })->asA('ClassA');
		$this->assertInstOf('ClassB', 'ClassA');
	}

	public function test_Register_ResolveBase()
	{
		$this->builder->register(function() { return new \ClassB; })->asA('ClassA');
		$this->assertInstOf('ClassB', 'ClassA');
	}

	public function test_RegisterAsCode()
	{
		$this->register('return new \\ClassA')->asA('ClassA');
		$this->assertInstOf('ClassA', 'ClassA');
	}

	public function test_RegisterAsCode_ResolveWithContainer()
	{
		$this->registerType('ClassB')->asA('InterfaceA1');
		$this->register('return $container->resolve(\'InterfaceA1\')')->asA('ClassA');
		$this->assertInstOf('ClassB', 'ClassA');
	}
}
