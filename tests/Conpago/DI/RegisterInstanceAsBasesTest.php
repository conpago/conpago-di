<?php

namespace Conpago\DI;

use ClassA;
use ClassB;
use ClassC;
use InterfaceA1;

require_once 'DITestCase.php';

class RegisterInstanceAsBasesTest extends DITestCase
{
	public function test_RegisterAsBases_ResolveBases()
	{
		$this->registerInstance(new ClassC)->asBases();
		$this->assertInstOf(ClassC::class, ClassA::class);
		$this->assertInstOf(ClassC::class, ClassB::class);
	}

	/**
	 * @expectedException \Conpago\DI\Exceptions\ObjectNotRegisteredException
	 */
	public function test_RegisterAsBases_ResolveSelf_Fail()
	{
		$this->registerInstance(new ClassC)->asBases();
		$this->resolve(ClassC::class);
	}

	/**
	 * @expectedException \Conpago\DI\Exceptions\ObjectNotRegisteredException
	 */
	public function test_RegisterAsMissingBases_ResolveSelf_Fail()
	{
		$this->registerInstance(new ClassA)->asBases();
		$this->resolve(ClassA::class);
	}

	/**
	 * @expectedException \Conpago\DI\Exceptions\ObjectNotRegisteredException
	 */
	public function test_RegisterAsBases_ResolveInterface_Fail()
	{
		$this->registerInstance(new ClassC)->asBases();
		$this->resolve(InterfaceA1::class);
	}

	public function test_RegisterAsBasesAndSelf_ResolveBaseAndSelf()
	{
		$this->registerInstance(new ClassC)->asBases()->asSelf();
		$this->assertInstOf(ClassC::class, ClassA::class);
		$this->assertInstOf(ClassC::class, ClassB::class);
		$this->assertInstOf(ClassC::class, ClassC::class);
	}

	/**
	 * @expectedException \Conpago\DI\Exceptions\ObjectNotRegisteredException
	 */
	public function test_RegisterAsBasesAndSelf_ResolveInterface_Fail()
	{
		$this->registerInstance(new ClassC)->asBases()->asSelf();
		$this->resolve(InterfaceA1::class);
	}

	public function test_RegisterAsBasesAndInterface_ResolveBaseAndInterface()
	{
		$this->registerInstance(new ClassC)->asBases()->asA(InterfaceA1::class);
		$this->assertInstOf(ClassC::class, ClassA::class);
		$this->assertInstOf(ClassC::class, InterfaceA1::class);
	}

	public function test_RegisterAsBases_ResolveAll()
	{
		$this->registerInstance(new ClassC)->asBases();
		$this->registerInstance(new ClassB)->asBases();
		$c = $this->resolveAll(ClassA::class);
		$this->assertEquals(2, count($c));
		$this->assertInstanceOf(ClassC::class, $c[0]);
		$this->assertInstanceOf(ClassB::class, $c[1]);
	}
}
