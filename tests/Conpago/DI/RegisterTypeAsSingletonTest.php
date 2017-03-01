<?php

namespace Conpago\DI;

use ClassA;
use ClassB;

require_once 'DITestCase.php';

class RegisterTypeAsSingletonTest extends DITestCase
{
	public function test_RegisterAsSingleton_ResolveSelf()
	{
		$this->registerType(ClassA::class)->singleInstance();
		$classA = $this->resolve(ClassA::class);
		$this->assertSame($classA, $this->resolve(ClassA::class));
	}

	public function test_RegisterAsSingletonAndBase_ResolveBase()
	{
		$this->registerType(ClassB::class)->asA(ClassA::class)->singleInstance();
		$classA = $this->resolve(ClassA::class);
		$this->assertSame($classA, $this->resolve(ClassA::class));
	}

	public function test_RegisterAsSingletonAndBase_ResolveSelf()
	{
		$this->registerType(ClassB::class)->asA(ClassA::class)->asSelf()->singleInstance();
		$classA = $this->resolve(ClassA::class);
		$this->assertSame($classA, $this->resolve(ClassB::class));
	}

	public function test_RegisterAsSingletonNamed_ResolveNamed()
	{
		$this->registerType(ClassB::class)->named('b')->singleInstance();
		$classA = $this->resolveNamed('b');
		$this->assertSame($classA, $this->resolveNamed('b'));
	}
}
