<?php

namespace Conpago\DI;

use ClassA;
use ClassB;

require_once 'DITestCase.php';

class RegisterTypeAsSelfTest extends DITestCase
{
	/**
	 * @expectedException \Conpago\DI\Exceptions\ObjectNotRegisteredException
	 */
	public function test_RegisterAsSelf_ResolveBase_Fail()
	{
		$this->registerType(ClassB::class)->asSelf();
		$this->resolve(ClassA::class);
	}

	public function test_RegisterAsSelf_ResolveSelf()
	{
		$this->registerType(ClassA::class)->asSelf();
		$this->assertInstOf(ClassA::class, ClassA::class);
	}

	public function test_RegisterAsSelfAndAsBase_ResolveSelfAndBase()
	{
		$this->registerType(ClassB::class)->asA(ClassA::class)->asSelf();
		$this->assertInstOf(ClassB::class, ClassA::class);
		$this->assertInstOf(ClassB::class, ClassB::class);
	}

	/**
	 * @expectedException \Conpago\DI\Exceptions\ObjectNotRegisteredException
	 */
	public function test_RegisterAsSelf_ResolveInterface_Fail()
	{
		$this->registerType(ClassB::class)->asSelf();
		$this->resolve(InterfaceA1::class);
	}

	public function test_RegisterAsSelf_ResolveAll()
	{
		$this->registerType(ClassA::class)->asSelf()->asA(ClassA::class);
		$all = $this->resolveAll(ClassA::class);
		$this->assertEquals(1, count($all));
	}
}
