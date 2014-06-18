<?php

namespace Saigon\Conpago\DI;

require_once 'DITestCase.php';

class RegisterTypeAsBasesTest extends DITestCase
{
	public function test_RegisterAsBases_ResolveBases()
	{
		$this->registerType('ClassC')->asBases();
		$this->assertInstOf('ClassC', 'ClassA');
		$this->assertInstOf('ClassC', 'ClassB');
	}

	/**
	 * @expectedException \Saigon\Conpago\DI\Exceptions\ObjectNotRegisteredException
	 */
	public function test_RegisterAsBases_ResolveSelf_Fail()
	{
		$this->registerType('ClassC')->asBases();
		$this->resolve('ClassC');
	}

	/**
	 * @expectedException \Saigon\Conpago\DI\Exceptions\ObjectNotRegisteredException
	 */
	public function test_RegisterAsMissingBases_ResolveSelf_Fail()
	{
		$this->registerType('ClassA')->asBases();
		$this->resolve('ClassA');
	}

	/**
	 * @expectedException \Saigon\Conpago\DI\Exceptions\ObjectNotRegisteredException
	 */
	public function test_RegisterAsBases_ResolveInterface_Fail()
	{
		$this->registerType('ClassC')->asBases();
		$this->resolve('InterfaceA1');
	}

	public function test_RegisterAsBasesAndSelf_ResolveBaseAndSelf()
	{
		$this->registerType('ClassC')->asBases()->asSelf();
		$this->assertInstOf('ClassC', 'ClassA');
		$this->assertInstOf('ClassC', 'ClassB');
		$this->assertInstOf('ClassC', 'ClassC');
	}

	/**
	 * @expectedException \Saigon\Conpago\DI\Exceptions\ObjectNotRegisteredException
	 */
	public function test_RegisterAsBasesAndSelf_ResolveInterface_Fail()
	{
		$this->registerType('ClassC')->asBases()->asSelf();
		$this->resolve('InterfaceA1');
	}

	public function test_RegisterAsBasesAndInterface_ResolveBaseAndInterface()
	{
		$this->registerType('ClassC')->asBases()->asA('InterfaceA1');
		$this->assertInstOf('ClassC', 'ClassA');
		$this->assertInstOf('ClassC', 'InterfaceA1');
	}

	public function test_RegisterAsBases_ResolveAll()
	{
		$this->registerType('ClassC')->asBases();
		$this->registerType('ClassB')->asBases();
		$c = $this->resolveAll('ClassA');
		$this->assertEquals(2, count($c));
		$this->assertInstanceOf('ClassC', $c[0]);
		$this->assertInstanceOf('ClassB', $c[1]);
	}
}
