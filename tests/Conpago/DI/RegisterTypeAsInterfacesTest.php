<?php

namespace Conpago\DI;

require_once 'DITestCase.php';

class RegisterTypeAsInterfacesTest extends DITestCase
{
	public function test_RegisterAsInterfaces_ResolveInterfaces()
	{
		$this->registerType('ClassC')->asInterfaces();
		$this->assertInstOf('ClassC', 'InterfaceA1');
		$this->assertInstOf('ClassC', 'InterfaceA2');
		$this->assertInstOf('ClassC', 'InterfaceB1');
		$this->assertInstOf('ClassC', 'InterfaceB2');
	}

	/**
	 * @expectedException \Conpago\DI\Exceptions\ObjectNotRegisteredException
	 */
	public function test_RegisterAsInterfaces_ResolveSelf_Fail()
	{
		$this->registerType('ClassC')->asInterfaces();
		$this->resolve('ClassC');
	}

	/**
	 * @expectedException \Conpago\DI\Exceptions\ObjectNotRegisteredException
	 */
	public function test_RegisterAsMissingInterfaces_ResolveSelf_Fail()
	{
		$this->registerType('ClassD')->asInterfaces();
		$this->resolve('ClassD');
	}

	/**
	 * @expectedException \Conpago\DI\Exceptions\ObjectNotRegisteredException
	 */
	public function test_RegisterAsInterfaces_ResolveBase_Fail()
	{
		$this->registerType('ClassC')->asInterfaces();
		$this->resolve('ClassB');
	}

	public function test_RegisterAsInterfacesAndSelf_ResolveSelf()
	{
		$this->registerType('ClassA')->asInterfaces()->asSelf();
		$this->assertInstOf('ClassA', 'InterfaceA1');
		$this->assertInstOf('ClassA', 'ClassA');
	}

	public function test_RegisterAsInterfacesAndBase_ResolveBase()
	{
		$this->registerType('ClassB')->asInterfaces()->asA('ClassA');
		$this->assertInstOf('ClassB', 'InterfaceA1');
		$this->assertInstOf('ClassB', 'ClassA');
	}

	public function test_RegisterAsInterfacesAndBases_ResolveSelf()
	{
		$this->registerType('ClassC')->asInterfaces()->asBases();
		$this->assertInstOf('ClassC', 'InterfaceA1');
		$this->assertInstOf('ClassC', 'ClassA');
	}

	public function test_RegisterAsInterfacesSelfAndBases_ResolveSelf()
	{
		$this->registerType('ClassC')->asInterfaces()->asSelf()->asBases();
		$this->assertInstOf('ClassC', 'InterfaceA1');
		$this->assertInstOf('ClassC', 'ClassA');
		$this->assertInstOf('ClassC', 'ClassC');
	}

	public function test_RegisterAsInterfaces_ResolveAll()
	{
		$this->registerType('ClassC')->asInterfaces();
		$this->registerType('ClassB')->asInterfaces();
		$this->registerType('ClassA')->asInterfaces();
		$c = $this->resolveAll('InterfaceA1');
		$this->assertEquals(3, count($c));
		$this->assertInstanceOf('ClassC', $c[0]);
		$this->assertInstanceOf('ClassB', $c[1]);
		$this->assertInstanceOf('ClassA', $c[2]);
	}
}
