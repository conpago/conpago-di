<?php

namespace Saigon\Conpago\DI;

require_once 'DITestCase.php';

class ResolveObjectLazyTest extends DITestCase
{
    public function test_ResolveTypeLazy()
    {
        $this->registerType('ClassD');
        $ld = $this->resolve(' Lazy < ClassD > ');
		$this->assertEquals(0, \ClassD::$instances);
		$this->assertInstanceOf('ClassD', $ld->getInstance());
		$this->assertEquals(1, \ClassD::$instances);
    }

    public function test_ResolveTypeFromNamespaceLazy()
    {
        $this->registerType('Test\\ClassA');
        $la = $this->resolve('Lazy<Test\\ClassA>');
		$this->assertInstanceOf('Test\\ClassA', $la->getInstance());
    }

    public function test_ResolveTypeFromSubNamespaceLazy()
    {
        $this->registerType('Test\\Sub\\ClassA');
        $la = $this->resolve('Lazy<Test\\Sub\\ClassA>');
		$this->assertInstanceOf('Test\\Sub\\ClassA', $la->getInstance());
    }

    public function test_ResolveInstanceLazy()
    {
        $this->registerInstance(new \ClassD);
		$this->assertEquals(1, \ClassD::$instances);
        $ld = $this->resolve('Lazy<ClassD>');
		$this->assertInstanceOf('ClassD', $ld->getInstance());
		$this->assertEquals(1, \ClassD::$instances);
    }

    public function test_ResolveClosureLazy()
    {
		$created = false;
		$this->register(function() use(&$created) { $created = true; return new \ClassA; })->asA('ClassA');
		$this->assertFalse($created);
        $la = $this->resolve('Lazy<ClassA>');
		$this->assertFalse($created);
		$this->assertInstanceOf('ClassA', $la->getInstance());
		$this->assertTrue($created);
    }
}
