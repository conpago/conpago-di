<?php

namespace Conpago\DI;

use ClassA;
use ClassB;
use ClassC;
use ClassD;
use InterfaceA1;

require_once 'DITestCase.php';

class RegisterTypeTest extends DITestCase
{
	public function test_RegisterUnknown()
	{
		$this->registerType('ClassAA_asdf');
        $this->assertTrue(TRUE);
	}

	/**
	 * @expectedException \ReflectionException
	 */
	public function test_RegisterUnknown_Resolve_Fail()
	{
		$this->registerType('ClassAA_asdf');
		$this->resolve('ClassAA_asdf');
	}

	/**
	 * @expectedException \Conpago\DI\Exceptions\RegisteringInvalidTypeException
	 */
	public function test_RegisterNotAType_Fail()
	{
		$a = new ClassA;
		$this->registerType($a);
	}

	public function test_RegisterPlain_ResolveSelf()
	{
		$this->registerType(ClassA::class);
		$this->assertInstOf(ClassA::class, ClassA::class);
	}

	/**
	 * @expectedException \Conpago\DI\Exceptions\ObjectNotRegisteredException
	 */
	public function test_RegisterPlain_ResolveBase_Fail()
	{
		$this->registerType(ClassA::class);
		$this->resolve(InterfaceA1::class);
	}

	public function test_RegisterPlain_AsUnrelated()
	{
		$this->registerType(ClassA::class)->asA(ClassB::class);
		$this->assertTrue(TRUE);
	}

	/**
	 * @expectedException \Conpago\DI\Exceptions\UnrelatedClassesException
	 */
	public function test_RegisterPlain_ResolveUnrelated_Fail()
	{
		$this->registerType(ClassA::class)->asA(ClassB::class);
		$this->resolve(ClassB::class);
	}

	public function test_RegisterAsOwnType_ResolveSelf()
	{
		$this->registerType(ClassA::class)->asA(ClassA::class);
		$this->assertInstOf(ClassA::class, ClassA::class);
	}

	public function test_RegisterAsInterface_ResolveInterface()
	{
		$this->registerType(ClassA::class)->asA(InterfaceA1::class);
		$this->assertInstOf(ClassA::class, InterfaceA1::class);
	}

	public function test_RegisterAsBase_ResolveBase()
	{
		$this->registerType(ClassB::class)->asA(ClassA::class);
		$this->assertInstOf(ClassB::class, ClassA::class);
	}

	public function test_RegisterAsFarBase_ResolveFarBase()
	{
		$this->registerType(ClassC::class)->asA(ClassA::class);
		$this->assertInstOf(ClassC::class, ClassA::class);
	}

	/**
	 * @expectedException \Conpago\DI\Exceptions\ObjectNotRegisteredException
	 */
	public function test_RegisterAsFarBase_ResolveNearBase_Fail()
	{
		$this->registerType(ClassC::class)->asA(ClassA::class);
		$this->resolve(ClassB::class);
	}

	/**
	 * @expectedException \Conpago\DI\Exceptions\ObjectNotRegisteredException
	 */
	public function test_RegisterAsBase_ResolveSelf_Fail()
	{
		$this->registerType(ClassB::class)->asA(ClassA::class);
		$this->resolve(ClassB::class);
	}

	/**
	 * @expectedException \Conpago\DI\Exceptions\ObjectNotRegisteredException
	 */
	public function test_RegisterAsBase_ResolveFarBase_Fail()
	{
		$this->registerType(ClassC::class)->asA(ClassB::class);
		$this->resolve(ClassA::class);
	}
	/**
	 * @expectedException \Conpago\DI\Exceptions\ObjectNotRegisteredException
	 */
	public function test_RegisterAsInterface_ResolveAnotherInterface_Fail()
	{
		$this->registerType(ClassA::class)->asA(InterfaceA1::class);
		$this->resolve(InterfaceA2::class);
	}

	public function test_RegisterAsBase_OverrideBase_ResolveBase()
	{
		$this->registerType(ClassB::class)->asA(ClassA::class);
		$this->registerType(ClassC::class)->asA(ClassA::class);
		$this->assertInstOf(ClassC::class, ClassA::class);
	}

	public function test_RegisterAsInterface_OverrideInterface_ResolveInterace()
	{
		$this->registerType(ClassB::class)->asA(InterfaceA1::class);
		$this->registerType(ClassC::class)->asA(InterfaceA1::class);
		$this->assertInstOf(ClassC::class, InterfaceA1::class);
	}

	public function test_RegisterAsBaseAndInterface_OverrideSelf_ResolveBaseAndInterface()
	{
		$this->registerType(ClassB::class)->asA(ClassA::class)->asA(InterfaceA1::class);
		$this->registerType(ClassB::class);
		$this->assertInstOf(ClassB::class, ClassA::class);
		$this->assertInstOf(ClassB::class, InterfaceA1::class);
		$this->assertInstOf(ClassB::class, ClassB::class);
	}

	public function test_RegisterMultiBase_ResolveMultiBase()
	{
		$this->registerType(ClassC::class)->asA(ClassA::class)->asA(ClassB::class)->asA(InterfaceA1::class);
		$this->assertInstOf(ClassC::class, ClassA::class);
		$this->assertInstOf(ClassC::class, ClassB::class);
		$this->assertInstOf(ClassC::class, InterfaceA1::class);
	}

	public function test_RegisterUnrelated_ResolveUnrelated()
	{
		$this->registerType(ClassB::class)->asA(ClassA::class);
		$this->registerType(ClassD::class);
		$this->assertInstOf(ClassB::class, ClassA::class);
		$this->assertInstOf(ClassD::class, ClassD::class);
	}

	public function test_RegisterManyPlain_ResolveAll()
	{
		$this->registerType(ClassB::class);
		$this->registerType(ClassB::class);
		$all = $this->resolveAll(ClassB::class);
		$this->assertEquals(2, count($all));
	}

	public function test_RegisterManyBase_ResolveAll1()
	{
		$this->registerType(ClassB::class)->asA(ClassA::class);
		$this->registerType(ClassB::class)->asA(ClassA::class);
		$all = $this->resolveAll(ClassA::class);
		$this->assertEquals(2, count($all));
	}

	public function test_RegisterManyBase_ResolveAll2()
	{
		$this->registerType(ClassB::class)->asA(ClassA::class)->asA(ClassA::class);
		$all = $this->resolveAll(ClassA::class);
		$this->assertEquals(1, count($all));
	}

	public function test_RegisterDelayedLoaded()
	{
		$this->registerType(\Delayed\ClassA::class);
		$this->registerType(ClassA::class);
		$this->resolve(ClassA::class);
        $this->assertTrue(TRUE);
	}

	/**
	 * @expectedException \ReflectionException
	 */
	public function test_RegisterDelayedLoaded_Resolve_Fail()
	{
		$this->registerType(\Delayed\ClassA::class);
		$this->resolve('Delayed\\ClassA');
	}

	public function test_RegisterDelayedLoaded_Resolve()
	{
		$this->registerType('Delayed\\ClassA');
		eval('namespace Delayed; class ClassA {}');
		$this->resolve('Delayed\\ClassA');
        $this->assertTrue(TRUE);
	}
}
