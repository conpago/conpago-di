<?php

namespace Conpago\DI;

use ClassA;
use ClassD;

require_once 'DITestCase.php';

class ResolveObjectLazyTest extends DITestCase
{
    public function test_ResolveTypeLazy()
    {
        $this->registerType(ClassD::class);
        $ld = $this->resolve(' Lazy < ClassD > ');
		$this->assertEquals(0, ClassD::$instances);
		$this->assertInstanceOf(ClassD::class, $ld->getInstance());
		$this->assertEquals(1, ClassD::$instances);
    }

    public function test_ResolveTypeFromNamespaceLazy()
    {
        $this->registerType(\Test\ClassA::class);
        $la = $this->resolve('Lazy<Test\\ClassA>');
		$this->assertInstanceOf(\Test\ClassA::class, $la->getInstance());
    }

    public function test_ResolveTypeFromSubNamespaceLazy()
    {
        $this->registerType(\Test\Sub\ClassA::class);
        $la = $this->resolve('Lazy<Test\\Sub\\ClassA>');
		$this->assertInstanceOf(\Test\Sub\ClassA::class, $la->getInstance());
    }

    public function test_ResolveInstanceLazy()
    {
        $this->registerInstance(new ClassD);
		$this->assertEquals(1, ClassD::$instances);
        $ld = $this->resolve('Lazy<ClassD>');
		$this->assertInstanceOf(ClassD::class, $ld->getInstance());
		$this->assertEquals(1, ClassD::$instances);
    }

    public function test_ResolveClosureLazy()
    {
		$created = false;
		$this->register(function() use(&$created) { $created = true; return new ClassA; })->asA(ClassA::class);
		$this->assertFalse($created);
        $la = $this->resolve('Lazy<ClassA>');
		$this->assertFalse($created);
		$this->assertInstanceOf(ClassA::class, $la->getInstance());
		$this->assertTrue($created);
    }
}
