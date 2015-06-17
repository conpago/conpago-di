<?php

namespace Conpago\DI;

require_once 'DITestCase.php';

class ResolveObjectFactoryTest extends DITestCase
{
    public function test_ResolveTypeFactory()
    {
        $this->registerType('ClassD');
        $fd = $this->resolve(' Factory < ClassD > ');
		$this->assertEquals(0, \ClassD::$instances);
		$this->assertInstanceOf('ClassD', $fd->createInstance());
		$this->assertEquals(1, \ClassD::$instances);
    }

    public function test_ResolveTypeFromNamespaceFactory()
    {
        $this->registerType('Test\\ClassA');
        $fa = $this->resolve('Factory<Test\\ClassA>');
		$this->assertInstanceOf('Test\\ClassA', $fa->createInstance());
    }

    public function test_ResolveTypeFromSubNamespaceFactory()
    {
        $this->registerType('Test\\Sub\\ClassA');
        $fa = $this->resolve('Factory<Test\\Sub\\ClassA>');
		$this->assertInstanceOf('Test\\Sub\\ClassA', $fa->createInstance());
    }

    public function test_ResolveInstanceFactory()
    {
        $this->registerInstance(new \ClassD);
		$this->assertEquals(1, \ClassD::$instances);
        $fd = $this->resolve('Factory<ClassD>');
		$this->assertInstanceOf('ClassD', $fd->createInstance());
		$this->assertEquals(1, \ClassD::$instances);
    }

    public function test_ResolveClosureFactory()
    {
		$created = false;
		$this->register(function() use(&$created) { $created = true; return new \ClassA; })->asA('ClassA');
		$this->assertFalse($created);
        $fa = $this->resolve('Factory<ClassA>');
		$this->assertFalse($created);
		$this->assertInstanceOf('ClassA', $fa->createInstance());
		$this->assertTrue($created);
    }
}
