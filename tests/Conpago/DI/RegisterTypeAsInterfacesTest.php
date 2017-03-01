<?php

namespace Conpago\DI;

use ClassA;
use ClassB;
use ClassC;
use ClassD;
use InterfaceA1;
use InterfaceA2;
use InterfaceB1;
use InterfaceB2;

require_once 'DITestCase.php';

class RegisterTypeAsInterfacesTest extends DITestCase
{
	public function test_RegisterAsInterfaces_ResolveInterfaces()
	{
		$this->registerType(ClassC::class)->asInterfaces();
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
		$this->registerType(ClassC::class)->asInterfaces();
		$this->resolve(ClassC::class);
	}

	/**
	 * @expectedException \Conpago\DI\Exceptions\ObjectNotRegisteredException
	 */
	public function test_RegisterAsMissingInterfaces_ResolveSelf_Fail()
	{
		$this->registerType(ClassD::class)->asInterfaces();
		$this->resolve(ClassD::class);
	}

	/**
	 * @expectedException \Conpago\DI\Exceptions\ObjectNotRegisteredException
	 */
	public function test_RegisterAsInterfaces_ResolveBase_Fail()
	{
		$this->registerType(ClassC::class)->asInterfaces();
		$this->resolve(ClassB::class);
	}

	public function test_RegisterAsInterfacesAndSelf_ResolveSelf()
	{
		$this->registerType(ClassA::class)->asInterfaces()->asSelf();
		$this->assertInstOf(ClassA::class, InterfaceA1::class);
		$this->assertInstOf(ClassA::class, ClassA::class);
	}

	public function test_RegisterAsInterfacesAndBase_ResolveBase()
	{
		$this->registerType(ClassB::class)->asInterfaces()->asA(ClassA::class);
		$this->assertInstOf(ClassB::class, InterfaceA1::class);
		$this->assertInstOf(ClassB::class, ClassA::class);
	}

	public function test_RegisterAsInterfacesAndBases_ResolveSelf()
	{
		$this->registerType(ClassC::class)->asInterfaces()->asBases();
		$this->assertInstOf(ClassC::class, InterfaceA1::class);
		$this->assertInstOf(ClassC::class, ClassA::class);
	}

	public function test_RegisterAsInterfacesSelfAndBases_ResolveSelf()
	{
		$this->registerType(ClassC::class)->asInterfaces()->asSelf()->asBases();
		$this->assertInstOf(ClassC::class, InterfaceA1::class);
		$this->assertInstOf(ClassC::class, ClassA::class);
		$this->assertInstOf(ClassC::class, ClassC::class);
	}

	public function test_RegisterAsInterfaces_ResolveAll()
	{
		$this->registerType(ClassC::class)->asInterfaces();
		$this->registerType(ClassB::class)->asInterfaces();
		$this->registerType(ClassA::class)->asInterfaces();
		$c = $this->resolveAll(InterfaceA1::class);
		$this->assertEquals(3, count($c));
		$this->assertInstanceOf(ClassC::class, $c[0]);
		$this->assertInstanceOf(ClassB::class, $c[1]);
		$this->assertInstanceOf(ClassA::class, $c[2]);
	}
}
