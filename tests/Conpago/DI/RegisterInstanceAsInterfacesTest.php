<?php

namespace Conpago\DI;

use ClassA;
use ClassB;
use ClassC;
use ClassD;
use InterfaceA1;

require_once 'DITestCase.php';

class RegisterInstanceAsInterfacesTest extends DITestCase
{
	public function test_RegisterAsInterfaces_ResolveInterfaces()
	{
		$this->registerInstance(new ClassC)->asInterfaces();
		$this->assertInstOf(ClassC::class, InterfaceA1::class);
		$this->assertInstOf(ClassC::class, InterfaceA2::class);
		$this->assertInstOf(ClassC::class, InterfaceB1::class);
		$this->assertInstOf(ClassC::class, InterfaceB2::class);
	}

	/**
	 * @expectedException \Conpago\DI\Exceptions\ObjectNotRegisteredException
	 */
	public function test_RegisterAsInterfaces_ResolveSelf_Fail()
	{
		$this->registerInstance(new ClassC)->asInterfaces();
		$this->resolve(ClassC::class);
	}

	/**
	 * @expectedException \Conpago\DI\Exceptions\ObjectNotRegisteredException
	 */
	public function test_RegisterAsMissingInterfaces_ResolveSelf_Fail()
	{
		$this->registerInstance(new ClassD)->asInterfaces();
		$this->resolve(ClassD::class);
	}

	/**
	 * @expectedException \Conpago\DI\Exceptions\ObjectNotRegisteredException
	 */
	public function test_RegisterAsInterfaces_ResolveBase_Fail()
	{
		$this->registerInstance(new ClassC)->asInterfaces();
		$this->resolve(ClassB::class);
	}

	public function test_RegisterAsInterfacesAndSelf_ResolveSelf()
	{
		$this->registerInstance(new ClassA)->asInterfaces()->asSelf();
		$this->assertInstOf(ClassA::class, InterfaceA1::class);
		$this->assertInstOf(ClassA::class, ClassA::class);
	}

	public function test_RegisterAsInterfacesAndBase_ResolveBase()
	{
		$this->registerInstance(new ClassB)->asInterfaces()->asA(ClassA::class);
		$this->assertInstOf(ClassB::class, InterfaceA1::class);
		$this->assertInstOf(ClassB::class, ClassA::class);
	}

	public function test_RegisterAsInterfacesAndBases_ResolveSelf()
	{
		$this->registerInstance(new ClassC)->asInterfaces()->asBases();
		$this->assertInstOf(ClassC::class, InterfaceA1::class);
		$this->assertInstOf(ClassC::class, ClassA::class);
	}

	public function test_RegisterAsInterfacesSelfAndBases_ResolveSelf()
	{
		$this->registerInstance(new ClassC)->asInterfaces()->asSelf()->asBases();
		$this->assertInstOf(ClassC::class, InterfaceA1::class);
		$this->assertInstOf(ClassC::class, ClassA::class);
		$this->assertInstOf(ClassC::class, ClassC::class);
	}

	public function test_RegisterAsInterfaces_ResolveAll()
	{
		$this->registerInstance(new ClassC)->asInterfaces();
		$this->registerInstance(new ClassB)->asInterfaces();
		$this->registerInstance(new ClassA)->asInterfaces();
		$c = $this->resolveAll(InterfaceA1::class);
		$this->assertEquals(3, count($c));
		$this->assertInstanceOf(ClassC::class, $c[0]);
		$this->assertInstanceOf(ClassB::class, $c[1]);
		$this->assertInstanceOf(ClassA::class, $c[2]);
	}
}
