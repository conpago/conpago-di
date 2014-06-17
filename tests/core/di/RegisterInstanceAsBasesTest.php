<?php

namespace DI;

require_once 'DITestCase.php';

class RegisterInstanceAsBasesTest extends DITestCase
{
	public function test_RegisterAsBases_ResolveBases()
	{
		$this->registerInstance(new \ClassC)->asBases();
		$this->assertInstOf('ClassC', 'ClassA');
		$this->assertInstOf('ClassC', 'ClassB');
	}

	/**
	 * @expectedException \DI\Exceptions\ObjectNotRegisteredException
	 */
	public function test_RegisterAsBases_ResolveSelf_Fail()
	{
		$this->registerInstance(new \ClassC)->asBases();
		$this->resolve('ClassC');
	}

	/**
	 * @expectedException \DI\Exceptions\ObjectNotRegisteredException
	 */
	public function test_RegisterAsMissingBases_ResolveSelf_Fail()
	{
		$this->registerInstance(new \ClassA)->asBases();
		$this->resolve('ClassA');
	}

	/**
	 * @expectedException \DI\Exceptions\ObjectNotRegisteredException
	 */
	public function test_RegisterAsBases_ResolveInterface_Fail()
	{
		$this->registerInstance(new \ClassC)->asBases();
		$this->resolve('InterfaceA1');
	}

	public function test_RegisterAsBasesAndSelf_ResolveBaseAndSelf()
	{
		$this->registerInstance(new \ClassC)->asBases()->asSelf();
		$this->assertInstOf('ClassC', 'ClassA');
		$this->assertInstOf('ClassC', 'ClassB');
		$this->assertInstOf('ClassC', 'ClassC');
	}

	/**
	 * @expectedException \DI\Exceptions\ObjectNotRegisteredException
	 */
	public function test_RegisterAsBasesAndSelf_ResolveInterface_Fail()
	{
		$this->registerInstance(new \ClassC)->asBases()->asSelf();
		$this->resolve('InterfaceA1');
	}

	public function test_RegisterAsBasesAndInterface_ResolveBaseAndInterface()
	{
		$this->registerInstance(new \ClassC)->asBases()->asA('InterfaceA1');
		$this->assertInstOf('ClassC', 'ClassA');
		$this->assertInstOf('ClassC', 'InterfaceA1');
	}

	public function test_RegisterAsBases_ResolveAll()
	{
		$this->registerInstance(new \ClassC)->asBases();
		$this->registerInstance(new \ClassB)->asBases();
		$c = $this->resolveAll('ClassA');
		$this->assertEquals(2, count($c));
		$this->assertInstanceOf('ClassC', $c[0]);
		$this->assertInstanceOf('ClassB', $c[1]);
	}
}
