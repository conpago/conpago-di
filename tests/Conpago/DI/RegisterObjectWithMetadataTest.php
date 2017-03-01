<?php

namespace Conpago\DI;

use ClassA;
use ClassB;
use ClassC;
use ClassM1;
use ClassM2;
use ClassM3;

require_once 'DITestCase.php';

class RegisterObjectWithMetadataTest extends DITestCase
{
	public function test_RegisterTypeWithMetadata()
	{
		$this->registerType(ClassA::class)->withMetadata('a');
		$this->registerType(ClassM1::class);
		$m = $this->resolve(ClassM1::class);
		$this->assertEquals('a', $m->a->getMetadata());
		$this->assertInstanceOf(ClassA::class, $m->a->getInstance());
}

	public function test_RegisterInstanceWithMetadata()
	{
		$this->registerInstance(new ClassA)->asA(ClassA::class)->withMetadata('a');
		$this->registerType(ClassM1::class);
		$m = $this->resolve(ClassM1::class);
		$this->assertEquals('a', $m->a->getMetadata());
		$this->assertInstanceOf(ClassA::class, $m->a->getInstance());
	}

	public function test_RegisterClosureWithMetadata()
	{
		$this->register(function() { return new ClassA; })->asA(ClassA::class)->withMetadata('a');
		$this->registerType(ClassM1::class);
		$m = $this->resolve(ClassM1::class);
		$this->assertEquals('a', $m->a->getMetadata());
		$this->assertInstanceOf(ClassA::class, $m->a->getInstance());
	}

	public function test_RegisterTypeWithMetadataOverwrite()
	{
		$this->registerType(ClassA::class)->withMetadata('a');
		$this->registerType(ClassA::class)->withMetadata('b');
        /** @var Meta $meta */
		$meta = $this->resolve('Meta<ClassA>');
        $this->assertEquals('b', $meta->getMetadata());
	}

	public function test_ResolveTypeCollectionWithMetadata()
	{
		$this->registerType(ClassA::class)->withMetadata('a');
		$this->registerType(ClassA::class)->withMetadata('b');
		$this->registerType(ClassM2::class);
		$m = $this->resolve(ClassM2::class);
		$this->assertTrue(is_array($m->a));
        /** @var Meta $meta0 */
		$meta0 = $m->a[0];
        $this->assertEquals('a', $meta0->getMetadata());
        /** @var Meta $meta1 */
        $meta1 = $m->a[1];
        $this->assertEquals('b', $meta1->getMetadata());
		$this->assertInstanceOf(ClassA::class, $meta0->getInstance());
	}

	public function test_ResolveMixedCollectionWithMetadata1()
	{
		$this->registerType(ClassA::class)->withMetadata('a');
		$this->registerInstance(new ClassA)->asA(ClassA::class)->withMetadata('b');
		$this->register(function() { return new ClassA; })->asA(ClassA::class)->withMetadata('c');
		$this->registerType(ClassM2::class);
		/** @var ClassM2 $m */
		$m = $this->resolve(ClassM2::class);
		$this->assertTrue(is_array($m->a));
        /** @var Meta $meta0 */
        $meta0 = $m->a[0];
        /** @var Meta $meta1 */
        $meta1 = $m->a[1];
        /** @var Meta $meta2 */
        $meta2 = $m->a[2];

		$this->assertEquals('a', $meta0->getMetadata());
		$this->assertEquals('b', $meta1->getMetadata());
		$this->assertEquals('c', $meta2->getMetadata());
		$this->assertInstanceOf(ClassA::class, $meta0->getInstance());
		$this->assertInstanceOf(ClassA::class, $meta1->getInstance());
		$this->assertInstanceOf(ClassA::class, $meta2->getInstance());
	}

	public function test_ResolveMixedCollectionWithMetadata2()
	{
		$this->registerType(ClassA::class)->asA(\InterfaceA1::class)->withMetadata('a');
		$this->registerInstance(new ClassB)->asA(\InterfaceA1::class)->withMetadata('b');
		$this->register(function() { return new ClassC; })->asA(\InterfaceA1::class)->withMetadata('c');
		$this->registerType(ClassM3::class);
		$m = $this->resolve(ClassM3::class);
		$this->assertTrue(is_array($m->a));
        /** @var Meta $meta0 */
		$meta0 = $m->a[0];
        /** @var Meta $meta1 */
        $meta1 = $m->a[1];
        /** @var Meta $meta2 */
        $meta2 = $m->a[2];
        $this->assertEquals('a', $meta0->getMetadata());
		$this->assertEquals('b', $meta1->getMetadata());
		$this->assertEquals('c', $meta2->getMetadata());
		$this->assertInstanceOf(ClassA::class, $meta0->getInstance());
		$this->assertFalse($meta0->getInstance() instanceof ClassB);
		$this->assertInstanceOf(ClassB::class, $meta1->getInstance());
		$this->assertFalse($meta1->getInstance() instanceof ClassC);
		$this->assertInstanceOf(ClassC::class, $meta2->getInstance());
	}

	public function test_ResolveFromEnclosingNamespaceWithMetadata()
	{
		$this->registerType(ClassA::class)->withMetadata('a');
		$this->registerType(\Test\ClassM1::class);
		$m = $this->resolve(\Test\ClassM1::class);
		$this->assertEquals('a', $m->a->getMetadata());
		$this->assertInstanceOf(ClassA::class, $m->a->getInstance());
	}

	public function test_ResolveFromSubNamespaceWithMetadata()
	{
		$this->registerType(\Test\Sub\ClassA::class)->withMetadata('a');
		$this->registerType(\Test\ClassM2::class);
		$m = $this->resolve(\Test\ClassM2::class);
		$this->assertEquals('a', $m->a->getMetadata());
		$this->assertInstanceOf(\Test\Sub\ClassA::class, $m->a->getInstance());
	}
}
