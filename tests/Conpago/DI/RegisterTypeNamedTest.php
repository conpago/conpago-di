<?php

namespace Conpago\DI;

require_once 'DITestCase.php';

class RegisterTypeNamedTest extends DITestCase
{
	public function test_RegisterNamed_ResolveNamed()
	{
		$this->registerType('ClassA')->named('test');
		$this->assertInstanceOf('ClassA', $this->resolveNamed('test'));
	}

	/**
	 * @expectedException \Conpago\DI\Exceptions\ObjectNotRegisteredException
	 */
	public function test_RegisterNamed_ResolveSelf_Fail()
	{
		$this->registerType('ClassA')->named('test');
		$this->resolve('ClassA');
	}

	public function test_RegisterNamedAndSelf_ResolveSelf()
	{
		$this->registerType('ClassA')->asSelf()->named('test');
		$this->assertInstOf('ClassA', 'ClassA');
		$this->assertInstanceOf('ClassA', $this->resolveNamed('test'));
	}

	public function test_RegisterNamedAndAsType_ResolveType()
	{
		$this->registerType('ClassB')->asA('ClassA')->named('test');
		$this->assertInstOf('ClassB', 'ClassA');
		$this->assertInstanceOf('ClassB', $this->resolveNamed('test'));
	}

	public function test_RegisterMultipleNamed_ResolveMultiple()
	{
		$this->registerType('ClassA')->named('test1');
		$this->registerType('ClassB')->named('test1');
		$this->registerType('ClassB')->named('test2');
		$this->assertInstanceOf('ClassB', $this->resolveNamed('test1'));
		$this->assertInstanceOf('ClassB', $this->resolveNamed('test2'));
	}
}
