<?php

namespace DI;

require_once 'DITestCase.php';

class OnActivatedTest extends DITestCase
{
	public function test_RegisterTypeWithOnActivated_HandleOnce()
	{
		$this->registerType('ClassA')->onActivated(function($a) { $a->val = 'test'; });
		$this->assertEquals('test', $this->resolve('ClassA')->val);
	}

	public function test_RegisterTypeWithOnActivated_HandleTwice()
	{
		$counter = 1;
		$this->registerType('ClassA')
			->onActivated(function($a) use(&$counter) { $a->val = $counter++; });
		$this->assertEquals(1, $counter);
		$this->assertEquals(1, $this->resolve('ClassA')->val);
		$this->assertEquals(2, $this->resolve('ClassA')->val);
		$this->assertEquals(3, $counter);
	}

	public function test_RegisterTypeWithOnActivated_HandleOnSingleton()
	{
		$counter = 1;
		$this->registerType('ClassA')
			->onActivated(function($a) use(&$counter) { $a->val = $counter++; })
			->singleInstance();
		$this->assertEquals(1, $this->resolve('ClassA')->val);
		$this->assertEquals(1, $this->resolve('ClassA')->val);
		$this->assertEquals(2, $counter);
	}

	public function test_RegisterTypeWithOnActivated_HandleWithContainer()
	{
		$b = null;
		$this->registerType('ClassB');
		$this->registerType('ClassA')
			->onActivated(function($a, $c) use(&$b) { $b = $c->resolve('ClassB'); });
		$this->resolve('ClassA');
		$this->assertInstanceOf('ClassB', $b);
	}

	public function test_RegisterClosureWithOnActivated_HandleOnce()
	{
		$this->register(function() { return new \ClassA; })
			->onActivated(function($a) { $a->val = 'test'; })
			->asA('ClassA');
		$this->assertEquals('test', $this->resolve('ClassA')->val);
	}

	public function test_RegisterClosureWithOnActivated_HandleTwice()
	{
		$counter = 1;
		$this->register(function() { return new \ClassA; })
			->onActivated(function($a) use(&$counter) { $a->val = $counter++; })
			->asA('ClassA');
		$this->assertEquals(1, $counter);
		$this->assertEquals(1, $this->resolve('ClassA')->val);
		$this->assertEquals(2, $this->resolve('ClassA')->val);
		$this->assertEquals(3, $counter);
	}

	public function test_RegisterClosureWithOnActivated_HandleOnSingleton()
	{
		$counter = 1;
		$this->register(function() { return new \ClassA; })
			->onActivated(function($a) use(&$counter) { $a->val = $counter++; })
			->singleInstance()
			->asA('ClassA');
		$this->assertEquals(1, $this->resolve('ClassA')->val);
		$this->assertEquals(1, $this->resolve('ClassA')->val);
		$this->assertEquals(2, $counter);
	}

	public function test_RegisterClosureWithOnActivated_HandleWithContainer()
	{
		$b = null;
		$this->registerType('ClassB');
		$this->register(function() { return new \ClassA; })
			->onActivated(function($a, $c) use(&$b) { $b = $c->resolve('ClassB'); })
			->asA('ClassA');
		$this->resolve('ClassA');
		$this->assertInstanceOf('ClassB', $b);
	}
}
