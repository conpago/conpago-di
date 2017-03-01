<?php

namespace Conpago\DI;

use ClassA;
use ClassB;

require_once 'DITestCase.php';

class RegisterClosureNamedTest extends DITestCase
{
	public function test_RegisterNamed_ResolveNamed()
	{
		$this->register(function() { return new ClassA; })->named('test');
		$this->assertInstanceOf(ClassA::class, $this->resolveNamed('test'));
	}

	/**
	 * @expectedException \Conpago\DI\Exceptions\ObjectNotRegisteredException
	 */
	public function test_RegisterNamed_ResolveSelf_Fail()
	{
		$this->register(function() { return new ClassA; })->named('test');
		$this->resolve(ClassA::class);
	}

	public function test_RegisterNamedAndAsType_ResolveType()
	{
		$this->register(function() { return new ClassB; })->asA(ClassA::class)->named('test');
		$this->assertInstOf(ClassB::class, ClassA::class);
		$this->assertInstanceOf(ClassB::class, $this->resolveNamed('test'));
	}

	public function test_RegisterMultipleNamed_ResolveMultiple()
	{
		$this->register(function() { return new ClassA; })->named('test1');
		$this->register(function() { return new ClassB; })->named('test1')->named('test2');
		$this->assertInstanceOf(ClassB::class, $this->resolveNamed('test1'));
		$this->assertInstanceOf(ClassB::class, $this->resolveNamed('test2'));
	}
}
