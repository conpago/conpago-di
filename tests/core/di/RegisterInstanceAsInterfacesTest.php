<?php

namespace DI;

require_once 'DITestCase.php';

class RegisterInstanceAsInterfacesTest extends DITestCase
{
	public function test_RegisterAsInterfaces_ResolveInterfaces()
	{
		$this->registerInstance(new \ClassC)->asInterfaces();
		$this->assertInstOf('ClassC', 'InterfaceA1');
		$this->assertInstOf('ClassC', 'InterfaceA2');
		$this->assertInstOf('ClassC', 'InterfaceB1');
		$this->assertInstOf('ClassC', 'InterfaceB2');
	}

	/**
	 * @expectedException \DI\Exceptions\ObjectNotRegisteredException
	 */
	public function test_RegisterAsInterfaces_ResolveSelf_Fail()
	{
		$this->registerInstance(new \ClassC)->asInterfaces();
		$this->resolve('ClassC');
	}

	/**
	 * @expectedException \DI\Exceptions\ObjectNotRegisteredException
	 */
	public function test_RegisterAsMissingInterfaces_ResolveSelf_Fail()
	{
		$this->registerInstance(new \ClassD)->asInterfaces();
		$this->resolve('ClassD');
	}

	/**
	 * @expectedException \DI\Exceptions\ObjectNotRegisteredException
	 */
	public function test_RegisterAsInterfaces_ResolveBase_Fail()
	{
		$this->registerInstance(new \ClassC)->asInterfaces();
		$this->resolve('ClassB');
	}

	public function test_RegisterAsInterfacesAndSelf_ResolveSelf()
	{
		$this->registerInstance(new \ClassA)->asInterfaces()->asSelf();
		$this->assertInstOf('ClassA', 'InterfaceA1');
		$this->assertInstOf('ClassA', 'ClassA');
	}

	public function test_RegisterAsInterfacesAndBase_ResolveBase()
	{
		$this->registerInstance(new \ClassB)->asInterfaces()->asA('ClassA');
		$this->assertInstOf('ClassB', 'InterfaceA1');
		$this->assertInstOf('ClassB', 'ClassA');
	}

	public function test_RegisterAsInterfacesAndBases_ResolveSelf()
	{
		$this->registerInstance(new \ClassC)->asInterfaces()->asBases();
		$this->assertInstOf('ClassC', 'InterfaceA1');
		$this->assertInstOf('ClassC', 'ClassA');
	}

	public function test_RegisterAsInterfacesSelfAndBases_ResolveSelf()
	{
		$this->registerInstance(new \ClassC)->asInterfaces()->asSelf()->asBases();
		$this->assertInstOf('ClassC', 'InterfaceA1');
		$this->assertInstOf('ClassC', 'ClassA');
		$this->assertInstOf('ClassC', 'ClassC');
	}

	public function test_RegisterAsInterfaces_ResolveAll()
	{
		$this->registerInstance(new \ClassC)->asInterfaces();
		$this->registerInstance(new \ClassB)->asInterfaces();
		$this->registerInstance(new \ClassA)->asInterfaces();
		$c = $this->resolveAll('InterfaceA1');
		$this->assertEquals(3, count($c));
		$this->assertInstanceOf('ClassC', $c[0]);
		$this->assertInstanceOf('ClassB', $c[1]);
		$this->assertInstanceOf('ClassA', $c[2]);
	}
}
