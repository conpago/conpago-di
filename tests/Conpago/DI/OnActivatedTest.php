<?php

namespace Conpago\DI;

use ClassA;
use ClassB;

require_once 'DITestCase.php';

class OnActivatedTest extends DITestCase
{
	public function test_RegisterTypeWithOnActivated_HandleOnce()
	{
		$this->registerType(ClassA::class)->onActivated(function($a) { $a->val = 'test'; });
		$this->assertEquals('test', $this->resolve(ClassA::class)->val);
	}

	public function test_RegisterTypeWithOnActivated_HandleTwice()
	{
		$counter = 1;
		$this->registerType(ClassA::class)
			->onActivated(function($a) use(&$counter) { $a->val = $counter++; });
		$this->assertEquals(1, $counter);
		$this->assertEquals(1, $this->resolve(ClassA::class)->val);
		$this->assertEquals(2, $this->resolve(ClassA::class)->val);
		$this->assertEquals(3, $counter);
	}

	public function test_RegisterTypeWithOnActivated_HandleOnSingleton()
	{
		$counter = 1;
		$this->registerType(ClassA::class)
			->onActivated(function($a) use(&$counter) { $a->val = $counter++; })
			->singleInstance();
		$this->assertEquals(1, $this->resolve(ClassA::class)->val);
		$this->assertEquals(1, $this->resolve(ClassA::class)->val);
		$this->assertEquals(2, $counter);
	}

	public function test_RegisterTypeWithOnActivated_HandleWithContainer()
	{
		$b = null;
		$this->registerType(ClassB::class);
		$this->registerType(ClassA::class)
			->onActivated(function(ClassA $a, IContainer $c) use(&$b) { $b = $c->resolve(ClassB::class); });
		$this->resolve(ClassA::class);
		$this->assertInstanceOf(ClassB::class, $b);
	}

	public function test_RegisterClosureWithOnActivated_HandleOnce()
	{
		$this->register(function() { return new ClassA; })
			->onActivated(function($a) { $a->val = 'test'; })
			->asA(ClassA::class);
		$this->assertEquals('test', $this->resolve(ClassA::class)->val);
	}

	public function test_RegisterClosureWithOnActivated_HandleTwice()
	{
		$counter = 1;
		$this->register(function() { return new ClassA; })
			->onActivated(function($a) use(&$counter) { $a->val = $counter++; })
			->asA(ClassA::class);
		$this->assertEquals(1, $counter);
		$this->assertEquals(1, $this->resolve(ClassA::class)->val);
		$this->assertEquals(2, $this->resolve(ClassA::class)->val);
		$this->assertEquals(3, $counter);
	}

	public function test_RegisterClosureWithOnActivated_HandleOnSingleton()
	{
		$counter = 1;
		$this->register(function() { return new ClassA; })
			->onActivated(function($a) use(&$counter) { $a->val = $counter++; })
			->singleInstance()
			->asA(ClassA::class);
		$this->assertEquals(1, $this->resolve(ClassA::class)->val);
		$this->assertEquals(1, $this->resolve(ClassA::class)->val);
		$this->assertEquals(2, $counter);
	}

	public function test_RegisterClosureWithOnActivated_HandleWithContainer()
	{
		$b = null;
		$this->registerType(ClassB::class);
		$this->register(function() { return new ClassA; })
			->onActivated(function($a, $c) use(&$b) { $b = $c->resolve(ClassB::class); })
			->asA(ClassA::class);
		$this->resolve(ClassA::class);
		$this->assertInstanceOf(ClassB::class, $b);
	}
}
