<?php

namespace Saigon\Conpago\DI;

require_once 'DITestCase.php';

class RegisterTypeWithParamsTest extends DITestCase
{
	public function test_RegisterWithParams_WithDefaultAtEnd()
	{
		$this->registerType('ClassE3')->withParams('testE3');
		$this->assertEquals('testE3', $this->resolve('ClassE3')->value);
		$this->assertEquals('e3', $this->resolve('ClassE3')->value2);
	}

	public function test_RegisterWithParams_WithDefaultFirst()
	{
		$this->registerType('ClassE3')->withParams(Parameter::def(), 'testE3');
		$this->assertEquals('e', $this->resolve('ClassE3')->value);
		$this->assertEquals('testE3', $this->resolve('ClassE3')->value2);
	}

	public function test_RegisterDelayedLoadedParams()
	{
		$this->registerType('ClassF1');
	}

	/**
	 * @expectedException \ReflectionException
	 */
	public function test_RegisterDelayedLoadedParams_Resolve_Fail()
	{
		$this->registerType('Delayed\\ClassB');
		$this->registerType('ClassF1');
		$this->resolve('ClassF1');
	}

	public function test_RegisterLazyDelayedLoadedParams_Resolve()
	{
		$this->registerType('Delayed\\ClassB');
		$this->registerType('ClassF2');
		$this->resolve('ClassF2');
	}

	public function test_RegisterWithLazyParam_ResolveNamed()
	{
		$counter = 0;
		$this->registerType('Test\\ClassA')->named('test')
			->onActivated(function() use(&$counter) { $counter++; });
		$this->registerType('ClassN32')->withParams(Parameter::named('test'));
		$c = $this->resolve('ClassN32');
		$this->assertEquals(0, $counter);
		$c->a->getInstance();
		$this->assertEquals(1, $counter);
	}

	public function test_RegisterWithMetaParam_ResolveNamed()
	{
		$counter = 0;
		$this->registerType('ClassA')->named('test')
			->withMetadata('a')
			->onActivated(function() use(&$counter) { $counter++; });
		$this->registerType('ClassM1')->withParams(Parameter::named('test'));
		$c = $this->resolve('ClassM1');
		$this->assertEquals(0, $counter);
		$this->assertEquals('a', $c->a->getMetadata());
		$c->a->getInstance();
		$this->assertEquals(1, $counter);
	}

	public function test_RegisterWithFactoryParam_ResolveNamed()
	{
		$counter = 0;
		$this->registerType('Test\\ClassA')->named('test')
			->onActivated(function() use(&$counter) { $counter++; });
		$this->registerType('ClassN22')->withParams(Parameter::named('test'));
		$c = $this->resolve('ClassN22');
		$this->assertEquals(0, $counter);
		$c->a->createInstance();
		$this->assertEquals(1, $counter);
	}
}
