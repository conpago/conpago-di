<?php

namespace Saigon\Conpago\DI;

require_once 'DITestCase.php';

class RegisterObjectWithMetadataTest extends DITestCase
{
	public function test_RegisterTypeWithMetadata()
	{
		$this->registerType('ClassA')->withMetadata('a');
		$this->registerType('ClassM1');
		$m = $this->resolve('ClassM1');
		$this->assertEquals('a', $m->a->getMetadata());
		$this->assertInstanceOf('ClassA', $m->a->getInstance());
}

	public function test_RegisterInstanceWithMetadata()
	{
		$this->registerInstance(new \ClassA)->asA('ClassA')->withMetadata('a');
		$this->registerType('ClassM1');
		$m = $this->resolve('ClassM1');
		$this->assertEquals('a', $m->a->getMetadata());
		$this->assertInstanceOf('ClassA', $m->a->getInstance());
	}

	public function test_RegisterClosureWithMetadata()
	{
		$this->register(function() { return new \ClassA; })->asA('ClassA')->withMetadata('a');
		$this->registerType('ClassM1');
		$m = $this->resolve('ClassM1');
		$this->assertEquals('a', $m->a->getMetadata());
		$this->assertInstanceOf('ClassA', $m->a->getInstance());
	}

	public function test_RegisterTypeWithMetadataOverwrite()
	{
		$this->registerType('ClassA')->withMetadata('a');
		$this->registerType('ClassA')->withMetadata('b');
		$this->assertEquals('b', $this->resolve('Meta<ClassA>')->getMetadata());
	}

	public function test_ResolveTypeCollectionWithMetadata()
	{
		$this->registerType('ClassA')->withMetadata('a');
		$this->registerType('ClassA')->withMetadata('b');
		$this->registerType('ClassM2');
		$m = $this->resolve('ClassM2');
		$this->assertTrue(is_array($m->a));
		$this->assertEquals('a', $m->a[0]->getMetadata());
		$this->assertEquals('b', $m->a[1]->getMetadata());
		$this->assertInstanceOf('ClassA', $m->a[0]->getInstance());
	}

	public function test_ResolveMixedCollectionWithMetadata1()
	{
		$this->registerType('ClassA')->withMetadata('a');
		$this->registerInstance(new \ClassA)->asA('ClassA')->withMetadata('b');
		$this->register(function() { return new \ClassA; })->asA('ClassA')->withMetadata('c');
		$this->registerType('ClassM2');
		$m = $this->resolve('ClassM2');
		$this->assertTrue(is_array($m->a));
		$this->assertEquals('a', $m->a[0]->getMetadata());
		$this->assertEquals('b', $m->a[1]->getMetadata());
		$this->assertEquals('c', $m->a[2]->getMetadata());
		$this->assertInstanceOf('ClassA', $m->a[0]->getInstance());
		$this->assertInstanceOf('ClassA', $m->a[1]->getInstance());
		$this->assertInstanceOf('ClassA', $m->a[2]->getInstance());
	}

	public function test_ResolveMixedCollectionWithMetadata2()
	{
		$this->registerType('ClassA')->asA('InterfaceA1')->withMetadata('a');
		$this->registerInstance(new \ClassB)->asA('InterfaceA1')->withMetadata('b');
		$this->register(function() { return new \ClassC; })->asA('InterfaceA1')->withMetadata('c');
		$this->registerType('ClassM3');
		$m = $this->resolve('ClassM3');
		$this->assertTrue(is_array($m->a));
		$this->assertEquals('a', $m->a[0]->getMetadata());
		$this->assertEquals('b', $m->a[1]->getMetadata());
		$this->assertEquals('c', $m->a[2]->getMetadata());
		$this->assertInstanceOf('ClassA', $m->a[0]->getInstance());
		$this->assertFalse($m->a[0]->getInstance() instanceof \ClassB);
		$this->assertInstanceOf('ClassB', $m->a[1]->getInstance());
		$this->assertFalse($m->a[1]->getInstance() instanceof \ClassC);
		$this->assertInstanceOf('ClassC', $m->a[2]->getInstance());
	}

	public function test_ResolveFromEnclosingNamespaceWithMetadata()
	{
		$this->registerType('ClassA')->withMetadata('a');
		$this->registerType('Test\\ClassM1');
		$m = $this->resolve('Test\\ClassM1');
		$this->assertEquals('a', $m->a->getMetadata());
		$this->assertInstanceOf('ClassA', $m->a->getInstance());
	}

	public function test_ResolveFromSubNamespaceWithMetadata()
	{
		$this->registerType('Test\\Sub\\ClassA')->withMetadata('a');
		$this->registerType('Test\\ClassM2');
		$m = $this->resolve('Test\\ClassM2');
		$this->assertEquals('a', $m->a->getMetadata());
		$this->assertInstanceOf('Test\\Sub\\ClassA', $m->a->getInstance());
	}
}
