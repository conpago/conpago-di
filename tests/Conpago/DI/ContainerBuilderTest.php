<?php

namespace Conpago\DI;

use ClassA;
use ClassB;
use ClassC;
use ClassD;
use ClassDD;
use ClassE;

require_once 'DITestCase.php';

class ContainerBuilderTest extends DITestCase
{
	public function testRegisterInstance_ResolveSelf()
	{
		$classD = new ClassD;
		$this->assertEquals(1, ClassD::$instances);
		$this->builder->registerInstance($classD);
		$container = $this->builder->build();
		$this->assertSame($classD, $container->resolve(ClassD::class));
		$this->assertEquals(1, ClassD::$instances);
	}

	public function testRegisterInstanceAsOwnType_ResolveSelf()
	{
		$classA = new ClassA;
		$this->builder->registerInstance($classA)->asA(ClassA::class);
		$container = $this->builder->build();
		$this->assertSame($classA, $container->resolve(ClassA::class));
	}

	/**
	 * @expectedException \Conpago\DI\Exceptions\RegisteringInvalidInstanceException
	 */
	public function testRegisterInvalidInstance_Fail()
	{
		$this->builder->registerInstance('fake');
		$this->builder->build();
	}

	public function testRegisterInstanceAsInterface_ResolveInterface()
	{
		$classA = new ClassA;
		$this->builder->registerInstance($classA)->asA(\InterfaceA1::class);
		$container = $this->builder->build();
		$this->assertSame($classA, $container->resolve(\InterfaceA1::class));
	}

	public function testRegisterInstanceAsBase_ResolveBase()
	{
		$classB = new ClassB;
		$this->builder->registerInstance($classB)->asA(ClassA::class);
		$container = $this->builder->build();
		$this->assertSame($classB, $container->resolve(ClassA::class));
	}

	/**
	 * @expectedException \Conpago\DI\Exceptions\UnrelatedClassesException
	 */
	public function testRegisterInstanceAsUnrelated_Fail()
	{
		$classB = new ClassB;
		$this->builder->registerInstance($classB)->asA(ClassC::class);
	}

	public function testRegisterInstanceNamed_ResolveNamed()
	{
		$classB = new ClassB;
		$this->builder->registerInstance($classB)->named('test');
		$this->assertInstanceOf(ClassB::class, $this->builder->build()->resolveNamed('test'));
	}

	/**
	 * @expectedException \Conpago\DI\Exceptions\ObjectNotRegisteredException
	 */
	public function testRegisterInstanceNamed_ResolveSelf_Fail()
	{
		$classB = new ClassB;
		$this->builder->registerInstance($classB)->named('test');
		$this->builder->build()->resolve(ClassB::class);
	}

	public function testRegisterInstanceNamedAndSelf_ResolveSelf()
	{
		$classB = new ClassB;
		$this->builder->registerInstance($classB)->asSelf()->named('test');
		$this->assertInstOf(ClassB::class, ClassB::class);
		$this->assertInstanceOf(ClassB::class, $this->container->resolveNamed('test'));
	}

	public function testRegisterNamed_ResolveNamed()
	{
		$this->builder->register(function() { return new ClassB; })->named('test');
		$this->assertInstanceOf(ClassB::class, $this->builder->build()->resolveNamed('test'));
	}

	/**
	 * @expectedException \Conpago\DI\Exceptions\ObjectNotRegisteredException
	 */
	public function testRegisterNamed_ResolveSelf_Fail()
	{
		$this->builder->register(function() { return new ClassB; })->named('test');
		$this->builder->build()->resolve(ClassB::class);
	}

	public function testRegisterNamedAndSelf_ResolveExplicit()
	{
		$this->builder->register(function() { return new ClassB; })->asA(ClassB::class)->named('test');
		$this->assertInstOf(ClassB::class, ClassB::class);
		$this->assertInstanceOf(ClassB::class, $this->container->resolveNamed('test'));
	}

	public function testRegisterType_ResolveCollection1()
	{
		$this->builder->registerType(ClassC::class)->asA(\InterfaceA1::class);
		$this->builder->registerType(ClassB::class)->asA(\InterfaceA1::class);
		$this->builder->registerType(ClassA::class)->asSelf()->asA(\InterfaceA1::class)->singleInstance();
		$this->builder->registerType(ClassDD::class);
		$container = $this->builder->build();
		$this->assertInstanceOf(ClassA::class, $container->resolve(\InterfaceA1::class));
		$dd = $container->resolve(ClassDD::class);
		$this->assertTrue(is_array($dd->intf));
		$this->assertEquals(3, count($dd->intf));
		$this->assertTrue(in_array($container->resolve(ClassA::class), $dd->intf));
	}

	public function testRegisterType_ResolveCollection2()
	{
		$this->builder->registerType(ClassB::class);
		$this->builder->registerType(ClassB::class);
		$this->assertEquals(2, count($this->builder->build()->resolveAll(ClassB::class)));
	}

	public function testRegisterType_ResolveCollection3()
	{
		$this->builder->registerType(ClassB::class)->asA(ClassA::class)->asSelf();
		$this->builder->registerType(ClassB::class)->asA(ClassA::class)->asSelf();
		$this->builder->registerType(ClassC::class)->asA(ClassA::class);
		$container = $this->builder->build();
		$this->assertEquals(2, count($container->resolveAll(ClassB::class)));
		$this->assertEquals(3, count($container->resolveAll(ClassA::class)));
	}

	public function testRegisterType_ResolveCollectionWithKeys1()
	{
		$this->builder->registerType(ClassB::class)->asSelf()->keyed('key1');
		$this->builder->registerType(ClassB::class)->asSelf()->keyed('key2');
		$container = $this->builder->build();
		$objects = $container->resolveAll(ClassB::class);
		$this->assertInstanceOf(ClassB::class, $objects['key1']);
		$this->assertInstanceOf(ClassB::class, $objects['key2']);
	}

	public function testRegisterType_ResolveCollectionWithKeys2()
	{
		$this->builder->registerType(ClassB::class)->asA(ClassA::class)->keyed('key1');
		$this->builder->registerType(ClassC::class)->asA(ClassA::class)->keyed('key2');
		$container = $this->builder->build();
		$objects = $container->resolveAll(ClassA::class);
		$this->assertInstanceOf(ClassB::class, $objects['key1']);
		$this->assertInstanceOf(ClassC::class, $objects['key2']);
	}

	public function testRegisterInstance_ResolveCollectionWithKeys()
	{
		$this->builder->registerInstance(new ClassE('key1'))->asSelf()->keyed('key1');
		$this->builder->registerInstance(new ClassE('key2'))->asSelf()->keyed('key2');
		$container = $this->builder->build();
		$objects = $container->resolveAll(ClassE::class);
		$this->assertInstanceOf(ClassE::class, $objects['key1']);
		$this->assertInstanceOf(ClassE::class, $objects['key2']);
		$this->assertEquals('key1', $objects['key1']->value);
		$this->assertEquals('key2', $objects['key2']->value);
	}

	public function testRegister_ResolveCollectionWithKeys()
	{
		$this->builder->register(function() { return new ClassE('key1'); })->asA(ClassE::class)->keyed('key1');
		$this->builder->register(function() { return new ClassE('key2'); })->asA(ClassE::class)->keyed('key2');
		$container = $this->builder->build();
		$objects = $container->resolveAll(ClassE::class);
		$this->assertInstanceOf(ClassE::class, $objects['key1']);
		$this->assertInstanceOf(ClassE::class, $objects['key2']);
		$this->assertEquals('key1', $objects['key1']->value);
		$this->assertEquals('key2', $objects['key2']->value);
	}

	public function testRegisterType_ResolveUnaryCollection()
	{
		$this->builder->registerType(ClassC::class)->asA(\InterfaceA1::class);
		$this->builder->registerType(ClassDD::class);
		$container = $this->builder->build();
		$this->assertInstanceOf(ClassC::class, $container->resolve(\InterfaceA1::class));
		$dd = $container->resolve(ClassDD::class);
		$this->assertTrue(is_array($dd->intf));
		$this->assertEquals(1, count($dd->intf));
		$this->assertInstanceOf(ClassC::class, $dd->intf[0]);
	}

	public function testRegister_ResolveUnaryCollection()
	{
		$this->builder->register(function() { return new ClassE('key1'); })->asA(ClassE::class);
		$container = $this->builder->build();
		$objects = $container->resolveAll(ClassE::class);
		$this->assertInstanceOf(ClassE::class, $objects[0]);
		$this->assertEquals('key1', $objects[0]->value);
	}

	public function testRegisterType_ResolveUnaryCollectionWithKey()
	{
		$this->builder->registerType(ClassB::class)->asA(ClassA::class)->keyed('key1');
		$container = $this->builder->build();
		$objects = $container->resolveAll(ClassA::class);
		$this->assertInstanceOf(ClassB::class, $objects['key1']);
	}

	public function testRegisterType_ResolveEmptyCollection()
	{
		$container = $this->builder->build();
		$objects = $container->resolveAll(ClassA::class);
		$this->assertTrue(is_array($objects));
		$this->assertTrue(empty($objects));
	}

	public function testRegisterType_ResolveEmptyCollectionParameter()
	{
		$this->builder->registerType(ClassDD::class);
		$container = $this->builder->build();
		$dd = $container->resolve(ClassDD::class);
		$this->assertTrue(is_array($dd->intf));
		$this->assertTrue(empty($dd->intf));
	}
}
