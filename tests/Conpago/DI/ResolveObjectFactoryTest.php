<?php

namespace Conpago\DI;

use ClassA;
use ClassD;

require_once 'DITestCase.php';

class ResolveObjectFactoryTest extends DITestCase
{
    public function test_ResolveTypeFactory()
    {
        $this->registerType(ClassD::class);
        $fd = $this->resolve(' Factory < ClassD > ');
		$this->assertEquals(0, ClassD::$instances);
		$this->assertInstanceOf(ClassD::class, $fd->createInstance());
		$this->assertEquals(1, ClassD::$instances);
    }

    public function test_ResolveTypeFromNamespaceFactory()
    {
        $this->registerType(\Test\ClassA::class);
        $fa = $this->resolve('Factory<Test\\ClassA>');
		$this->assertInstanceOf(\Test\ClassA::class, $fa->createInstance());
    }

    public function test_ResolveTypeFromSubNamespaceFactory()
    {
        $this->registerType(\Test\Sub\ClassA::class);
        $fa = $this->resolve('Factory<Test\\Sub\\ClassA>');
		$this->assertInstanceOf(\Test\Sub\ClassA::class, $fa->createInstance());
    }

    public function test_ResolveInstanceFactory()
    {
        $this->registerInstance(new ClassD);
		$this->assertEquals(1, ClassD::$instances);
        $fd = $this->resolve('Factory<ClassD>');
		$this->assertInstanceOf(ClassD::class, $fd->createInstance());
		$this->assertEquals(1, ClassD::$instances);
    }

    public function test_ResolveClosureFactory()
    {
		$created = false;
		$this->register(function() use(&$created) { $created = true; return new ClassA; })->asA(ClassA::class);
		$this->assertFalse($created);
        $fa = $this->resolve('Factory<ClassA>');
		$this->assertFalse($created);
		$this->assertInstanceOf(ClassA::class, $fa->createInstance());
		$this->assertTrue($created);
    }
}
