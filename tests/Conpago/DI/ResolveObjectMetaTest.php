<?php

namespace Conpago\DI;

use ClassA;
use ClassD;

require_once 'DITestCase.php';

class ResolveObjectMetaTest extends DITestCase
{
    public function test_ResolveTypeMeta()
    {
        $this->registerType(ClassD::class)->withMetadata('d');
		$this->assertEquals(0, ClassD::$instances);
        /** @var Meta $md */
		$md = $this->resolve(' Meta < ClassD > ');
		$this->assertEquals('d', $md->getMetadata());
		$this->assertEquals(0, ClassD::$instances);
		$this->assertInstanceOf(ClassD::class, $md->getInstance());
		$this->assertEquals(1, ClassD::$instances);
    }

    public function test_ResolveTypeFromNamespaceMeta()
    {
        $this->registerType(\Test\ClassA::class)->withMetadata('a');
        /** @var Meta $ma */
        $ma = $this->resolve('Meta<Test\\ClassA>');
		$this->assertEquals('a', $ma->getMetadata());
		$this->assertInstanceOf(\Test\ClassA::class, $ma->getInstance());
    }

    public function test_ResolveTypeFromSubNamespaceMeta()
    {
        $this->registerType(\Test\Sub\ClassA::class)->withMetadata('a');
        /** @var Meta $ma */
        $ma = $this->resolve('Meta<Test\\Sub\\ClassA>');
		$this->assertEquals('a', $ma->getMetadata());
		$this->assertInstanceOf(\Test\Sub\ClassA::class, $ma->getInstance());
    }

    public function test_ResolveInstanceMeta()
    {
        $this->registerInstance(new ClassD)->withMetadata('d');
		$this->assertEquals(1, ClassD::$instances);
        /** @var Meta $md */
        $md = $this->resolve('Meta<ClassD>');
        $this->assertEquals('d', $md->getMetadata());
		$this->assertInstanceOf(ClassD::class, $md->getInstance());
		$this->assertEquals(1, ClassD::$instances);
    }

    public function test_ResolveClosureMeta()
    {
		$created = false;
		$this->register(function() use(&$created) { $created = true; return new ClassA; })
			->asA(ClassA::class)->withMetadata('a');
		$this->assertFalse($created);
        /** @var Meta $ma */
		$ma = $this->resolve('Meta<ClassA>');
		$this->assertEquals('a', $ma->getMetadata());
		$this->assertFalse($created);
		$this->assertInstanceOf(ClassA::class, $ma->getInstance());
		$this->assertTrue($created);
    }
}
