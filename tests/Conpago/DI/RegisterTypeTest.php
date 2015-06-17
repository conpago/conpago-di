<?php

namespace Conpago\DI;

require_once 'DITestCase.php';

class RegisterTypeTest extends DITestCase
{
	public function test_RegisterUnknown()
	{
		$this->registerType('ClassAA_asdf');
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
		$a = new \ClassA;
		$this->registerType($a);
	}

	public function test_RegisterPlain_ResolveSelf()
	{
		$this->registerType('ClassA');
		$this->assertInstOf('ClassA', 'ClassA');
	}

	/**
	 * @expectedException \Conpago\DI\Exceptions\ObjectNotRegisteredException
	 */
	public function test_RegisterPlain_ResolveBase_Fail()
	{
		$this->registerType('ClassA');
		$this->resolve('InterfaceA1');
	}

	public function test_RegisterPlain_AsUnrelated()
	{
		$this->registerType('ClassA')->asA('ClassB');
	}

	/**
	 * @expectedException \Conpago\DI\Exceptions\UnrelatedClassesException
	 */
	public function test_RegisterPlain_ResolveUnrelated_Fail()
	{
		$this->registerType('ClassA')->asA('ClassB');
		$this->resolve('ClassB');
	}

	public function test_RegisterAsOwnType_ResolveSelf()
	{
		$this->registerType('ClassA')->asA('ClassA');
		$this->assertInstOf('ClassA', 'ClassA');
	}

	public function test_RegisterAsInterface_ResolveInterface()
	{
		$this->registerType('ClassA')->asA('InterfaceA1');
		$this->assertInstOf('ClassA', 'InterfaceA1');
	}

	public function test_RegisterAsBase_ResolveBase()
	{
		$this->registerType('ClassB')->asA('ClassA');
		$this->assertInstOf('ClassB', 'ClassA');
	}

	public function test_RegisterAsFarBase_ResolveFarBase()
	{
		$this->registerType('ClassC')->asA('ClassA');
		$this->assertInstOf('ClassC', 'ClassA');
	}

	/**
	 * @expectedException \Conpago\DI\Exceptions\ObjectNotRegisteredException
	 */
	public function test_RegisterAsFarBase_ResolveNearBase_Fail()
	{
		$this->registerType('ClassC')->asA('ClassA');
		$this->resolve('ClassB');
	}

	/**
	 * @expectedException \Conpago\DI\Exceptions\ObjectNotRegisteredException
	 */
	public function test_RegisterAsBase_ResolveSelf_Fail()
	{
		$this->registerType('ClassB')->asA('ClassA');
		$this->resolve('ClassB');
	}

	/**
	 * @expectedException \Conpago\DI\Exceptions\ObjectNotRegisteredException
	 */
	public function test_RegisterAsBase_ResolveFarBase_Fail()
	{
		$this->registerType('ClassC')->asA('ClassB');
		$this->resolve('ClassA');
	}
	/**
	 * @expectedException \Conpago\DI\Exceptions\ObjectNotRegisteredException
	 */
	public function test_RegisterAsInterface_ResolveAnotherInterface_Fail()
	{
		$this->registerType('ClassA')->asA('InterfaceA1');
		$this->resolve('InterfaceA2');
	}

	public function test_RegisterAsBase_OverrideBase_ResolveBase()
	{
		$this->registerType('ClassB')->asA('ClassA');
		$this->registerType('ClassC')->asA('ClassA');
		$this->assertInstOf('ClassC', 'ClassA');
	}

	public function test_RegisterAsInterface_OverrideInterface_ResolveInterace()
	{
		$this->registerType('ClassB')->asA('InterfaceA1');
		$this->registerType('ClassC')->asA('InterfaceA1');
		$this->assertInstOf('ClassC', 'InterfaceA1');
	}

	public function test_RegisterAsBaseAndInterface_OverrideSelf_ResolveBaseAndInterface()
	{
		$this->registerType('ClassB')->asA('ClassA')->asA('InterfaceA1');
		$this->registerType('ClassB');
		$this->assertInstOf('ClassB', 'ClassA');
		$this->assertInstOf('ClassB', 'InterfaceA1');
		$this->assertInstOf('ClassB', 'ClassB');
	}

	public function test_RegisterMultiBase_ResolveMultiBase()
	{
		$this->registerType('ClassC')->asA('ClassA')->asA('ClassB')->asA('InterfaceA1');
		$this->assertInstOf('ClassC', 'ClassA');
		$this->assertInstOf('ClassC', 'ClassB');
		$this->assertInstOf('ClassC', 'InterfaceA1');
	}

	public function test_RegisterUnrelated_ResolveUnrelated()
	{
		$this->registerType('ClassB')->asA('ClassA');
		$this->registerType('ClassD');
		$this->assertInstOf('ClassB', 'ClassA');
		$this->assertInstOf('ClassD', 'ClassD');
	}

	public function test_RegisterManyPlain_ResolveAll()
	{
		$this->registerType('ClassB');
		$this->registerType('ClassB');
		$all = $this->resolveAll('ClassB');
		$this->assertEquals(2, count($all));
	}

	public function test_RegisterManyBase_ResolveAll1()
	{
		$this->registerType('ClassB')->asA('ClassA');
		$this->registerType('ClassB')->asA('ClassA');
		$all = $this->resolveAll('ClassA');
		$this->assertEquals(2, count($all));
	}

	public function test_RegisterManyBase_ResolveAll2()
	{
		$this->registerType('ClassB')->asA('ClassA')->asA('ClassA');
		$all = $this->resolveAll('ClassA');
		$this->assertEquals(1, count($all));
	}

	public function test_RegisterDelayedLoaded()
	{
		$this->registerType('Delayed\\ClassA');
		$this->registerType('ClassA');
		$this->resolve('ClassA');
	}

	/**
	 * @expectedException \ReflectionException
	 */
	public function test_RegisterDelayedLoaded_Resolve_Fail()
	{
		$this->registerType('Delayed\\ClassA');
		$this->resolve('Delayed\\ClassA');
	}

	public function test_RegisterDelayedLoaded_Resolve()
	{
		$this->registerType('Delayed\\ClassA');
		eval('namespace Delayed; class ClassA {}');
		$this->resolve('Delayed\\ClassA');
	}
}
