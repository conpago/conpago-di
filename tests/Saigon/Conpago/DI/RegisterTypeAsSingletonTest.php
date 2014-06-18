<?php

namespace Saigon\Conpago\DI;

require_once 'DITestCase.php';

class RegisterTypeAsSingletonTest extends DITestCase
{
	public function test_RegisterAsSingleton_ResolveSelf()
	{
		$this->registerType('ClassA')->singleInstance();
		$classA = $this->resolve('ClassA');
		$this->assertSame($classA, $this->resolve('ClassA'));
	}

	public function test_RegisterAsSingletonAndBase_ResolveBase()
	{
		$this->registerType('ClassB')->asA('ClassA')->singleInstance();
		$classA = $this->resolve('ClassA');
		$this->assertSame($classA, $this->resolve('ClassA'));
	}

	public function test_RegisterAsSingletonAndBase_ResolveSelf()
	{
		$this->registerType('ClassB')->asA('ClassA')->asSelf()->singleInstance();
		$classA = $this->resolve('ClassA');
		$this->assertSame($classA, $this->resolve('ClassB'));
	}

	public function test_RegisterAsSingletonNamed_ResolveNamed()
	{
		$this->registerType('ClassB')->named('b')->singleInstance();
		$classA = $this->resolveNamed('b');
		$this->assertSame($classA, $this->resolveNamed('b'));
	}
}
