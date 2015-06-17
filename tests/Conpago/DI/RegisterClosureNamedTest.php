<?php

namespace Conpago\DI;

require_once 'DITestCase.php';

class RegisterClosureNamedTest extends DITestCase
{
	public function test_RegisterNamed_ResolveNamed()
	{
		$this->register(function() { return new \ClassA; })->named('test');
		$this->assertInstanceOf('ClassA', $this->resolveNamed('test'));
	}

	/**
	 * @expectedException \Conpago\DI\Exceptions\ObjectNotRegisteredException
	 */
	public function test_RegisterNamed_ResolveSelf_Fail()
	{
		$this->register(function() { return new \ClassA; })->named('test');
		$this->resolve('ClassA');
	}

	public function test_RegisterNamedAndAsType_ResolveType()
	{
		$this->register(function() { return new \ClassB; })->asA('ClassA')->named('test');
		$this->assertInstOf('ClassB', 'ClassA');
		$this->assertInstanceOf('ClassB', $this->resolveNamed('test'));
	}

	public function test_RegisterMultipleNamed_ResolveMultiple()
	{
		$this->register(function() { return new \ClassA; })->named('test1');
		$this->register(function() { return new \ClassB; })->named('test1')->named('test2');
		$this->assertInstanceOf('ClassB', $this->resolveNamed('test1'));
		$this->assertInstanceOf('ClassB', $this->resolveNamed('test2'));
	}
}
