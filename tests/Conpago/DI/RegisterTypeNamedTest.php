<?php

namespace Conpago\DI;

use ClassA;
use ClassB;

require_once 'DITestCase.php';

class RegisterTypeNamedTest extends DITestCase
{
	public function test_RegisterNamed_ResolveNamed()
	{
		$this->registerType(ClassA::class)->named('test');
		$this->assertInstanceOf(ClassA::class, $this->resolveNamed('test'));
	}

	/**
	 * @expectedException \Conpago\DI\Exceptions\ObjectNotRegisteredException
	 */
	public function test_RegisterNamed_ResolveSelf_Fail()
	{
		$this->registerType(ClassA::class)->named('test');
		$this->resolve(ClassA::class);
	}

	public function test_RegisterNamedAndSelf_ResolveSelf()
	{
		$this->registerType(ClassA::class)->asSelf()->named('test');
		$this->assertInstOf(ClassA::class, ClassA::class);
		$this->assertInstanceOf(ClassA::class, $this->resolveNamed('test'));
	}

	public function test_RegisterNamedAndAsType_ResolveType()
	{
		$this->registerType(ClassB::class)->asA(ClassA::class)->named('test');
		$this->assertInstOf(ClassB::class, ClassA::class);
		$this->assertInstanceOf(ClassB::class, $this->resolveNamed('test'));
	}

	public function test_RegisterMultipleNamed_ResolveMultiple()
	{
		$this->registerType(ClassA::class)->named('test1');
		$this->registerType(ClassB::class)->named('test1');
		$this->registerType(ClassB::class)->named('test2');
		$this->assertInstanceOf(ClassB::class, $this->resolveNamed('test1'));
		$this->assertInstanceOf(ClassB::class, $this->resolveNamed('test2'));
	}
}
