<?php

namespace Conpago\DI;

use ClassA;
use ClassB;
use ClassD;
use InterfaceA1;

require_once 'DITestCase.php';

class RegisterClosureTest extends DITestCase
{
	public function test_Register_ResolveSelf()
	{
		$this->builder->register(function() { return new ClassD; })->asA(ClassD::class);
		$this->assertEquals(0, ClassD::$instances);
		$container = $this->builder->build();
		$this->assertEquals(0, ClassD::$instances);
		$this->assertInstanceOf(ClassD::class, $container->resolve(ClassD::class));
		$this->assertEquals(1, ClassD::$instances);
	}

	/**
	 * @expectedException \Conpago\DI\Exceptions\UnrelatedClassesException
	 */
	public function test_Register_ResolveUnrelated_Fail()
	{
		$this->builder->register(function() { return new ClassD; })->asA(ClassA::class);
		$this->assertInstOf(ClassD::class, ClassA::class);
	}

	public function test_Register_ResolveWithContainer()
	{
		$this->builder->registerType(ClassB::class);
		$this->builder->register(function(IContainer $c) { return $c->resolve(ClassB::class); })->asA(ClassA::class);
		$this->assertInstOf(ClassB::class, ClassA::class);
	}

	public function test_Register_ResolveBase()
	{
		$this->builder->register(function() { return new ClassB; })->asA(ClassA::class);
		$this->assertInstOf(ClassB::class, ClassA::class);
	}

	public function test_RegisterAsCode()
	{
		$this->register('return new \\ClassA')->asA(ClassA::class);
		$this->assertInstOf(ClassA::class, ClassA::class);
	}

	public function test_RegisterAsCode_ResolveWithContainer()
	{
		$this->registerType(ClassB::class)->asA(InterfaceA1::class);
		$this->register('return $container->resolve(\'InterfaceA1\')')->asA(ClassA::class);
		$this->assertInstOf(ClassB::class, ClassA::class);
	}
}
