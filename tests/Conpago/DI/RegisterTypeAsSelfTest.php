<?php

namespace Saigon\Conpago\DI;

require_once 'DITestCase.php';

class RegisterTypeAsSelfTest extends DITestCase
{
	/**
	 * @expectedException \Saigon\Conpago\DI\Exceptions\ObjectNotRegisteredException
	 */
	public function test_RegisterAsSelf_ResolveBase_Fail()
	{
		$this->registerType('ClassB')->asSelf();
		$this->resolve('ClassA');
	}

	public function test_RegisterAsSelf_ResolveSelf()
	{
		$this->registerType('ClassA')->asSelf();
		$this->assertInstOf('ClassA', 'ClassA');
	}

	public function test_RegisterAsSelfAndAsBase_ResolveSelfAndBase()
	{
		$this->registerType('ClassB')->asA('ClassA')->asSelf();
		$this->assertInstOf('ClassB', 'ClassA');
		$this->assertInstOf('ClassB', 'ClassB');
	}

	/**
	 * @expectedException \Saigon\Conpago\DI\Exceptions\ObjectNotRegisteredException
	 */
	public function test_RegisterAsSelf_ResolveInterface_Fail()
	{
		$this->registerType('ClassB')->asSelf();
		$this->resolve('InterfaceA1');
	}

	public function test_RegisterAsSelf_ResolveAll()
	{
		$this->registerType('ClassA')->asSelf()->asA('ClassA');
		$all = $this->resolveAll('ClassA');
		$this->assertEquals(1, count($all));
	}
}
