<?php

namespace DI;

require_once 'DITestCase.php';

class ContainerBuilderTest extends DITestCase
{
	public function testRegisterInstance_ResolveSelf()
	{
		$classD = new \ClassD;
		$this->assertEquals(1, \ClassD::$instances);
		$this->builder->registerInstance($classD);
		$container = $this->builder->build();
		$this->assertSame($classD, $container->resolve('ClassD'));
		$this->assertEquals(1, \ClassD::$instances);
	}

	public function testRegisterInstanceAsOwnType_ResolveSelf()
	{
		$classA = new \ClassA;
		$this->builder->registerInstance($classA)->asA('ClassA');
		$container = $this->builder->build();
		$this->assertSame($classA, $container->resolve('ClassA'));
	}

	/**
	 * @expectedException \DI\Exceptions\RegisteringInvalidInstanceException
	 */
	public function testRegisterInvalidInstance_Fail()
	{
		$this->builder->registerInstance('fake');
		$this->builder->build();
	}

	public function testRegisterInstanceAsInterface_ResolveInterface()
	{
		$classA = new \ClassA;
		$this->builder->registerInstance($classA)->asA('InterfaceA1');
		$container = $this->builder->build();
		$this->assertSame($classA, $container->resolve('InterfaceA1'));
	}

	public function testRegisterInstanceAsBase_ResolveBase()
	{
		$classB = new \ClassB;
		$this->builder->registerInstance($classB)->asA('ClassA');
		$container = $this->builder->build();
		$this->assertSame($classB, $container->resolve('ClassA'));
	}

	/**
	 * @expectedException \DI\Exceptions\UnrelatedClassesException
	 */
	public function testRegisterInstanceAsUnrelated_Fail()
	{
		$classB = new \ClassB;
		$this->builder->registerInstance($classB)->asA('ClassC');
	}

	public function testRegisterInstanceNamed_ResolveNamed()
	{
		$classB = new \ClassB;
		$this->builder->registerInstance($classB)->named('test');
		$this->assertInstanceOf('ClassB', $this->builder->build()->resolveNamed('test'));
	}

	/**
	 * @expectedException \DI\Exceptions\ObjectNotRegisteredException
	 */
	public function testRegisterInstanceNamed_ResolveSelf_Fail()
	{
		$classB = new \ClassB;
		$this->builder->registerInstance($classB)->named('test');
		$this->builder->build()->resolve('ClassB');
	}

	public function testRegisterInstanceNamedAndSelf_ResolveSelf()
	{
		$classB = new \ClassB;
		$this->builder->registerInstance($classB)->asSelf()->named('test');
		$this->assertInstOf('ClassB', 'ClassB');
		$this->assertInstanceOf('ClassB', $this->container->resolveNamed('test'));
	}

	public function testRegisterNamed_ResolveNamed()
	{
		$this->builder->register(function() { return new \ClassB; })->named('test');
		$this->assertInstanceOf('ClassB', $this->builder->build()->resolveNamed('test'));
	}

	/**
	 * @expectedException \DI\Exceptions\ObjectNotRegisteredException
	 */
	public function testRegisterNamed_ResolveSelf_Fail()
	{
		$this->builder->register(function() { return new \ClassB; })->named('test');
		$this->builder->build()->resolve('ClassB');
	}

	public function testRegisterNamedAndSelf_ResolveExplicite()
	{
		$this->builder->register(function() { return new \ClassB; })->asA('ClassB')->named('test');
		$this->assertInstOf('ClassB', 'ClassB');
		$this->assertInstanceOf('ClassB', $this->container->resolveNamed('test'));
	}

	public function testRegisterType_ResolveCollection1()
	{
		$this->builder->registerType('ClassC')->asA('InterfaceA1');
		$this->builder->registerType('ClassB')->asA('InterfaceA1');
		$this->builder->registerType('ClassA')->asSelf()->asA('InterfaceA1')->singleInstance();
		$this->builder->registerType('ClassDD');
		$container = $this->builder->build();
		$this->assertInstanceOf('ClassA', $container->resolve('InterfaceA1'));
		$dd = $container->resolve('ClassDD');
		$this->assertTrue(is_array($dd->intf));
		$this->assertEquals(3, count($dd->intf));
		$this->assertTrue(in_array($container->resolve('ClassA'), $dd->intf));
	}

	public function testRegisterType_ResolveCollection2()
	{
		$this->builder->registerType('ClassB');
		$this->builder->registerType('ClassB');
		$this->assertEquals(2, count($this->builder->build()->resolveAll('ClassB')));
	}

	public function testRegisterType_ResolveCollection3()
	{
		$this->builder->registerType('ClassB')->asA('ClassA')->asSelf();
		$this->builder->registerType('ClassB')->asA('ClassA')->asSelf();
		$this->builder->registerType('ClassC')->asA('ClassA');
		$container = $this->builder->build();
		$this->assertEquals(2, count($container->resolveAll('ClassB')));
		$this->assertEquals(3, count($container->resolveAll('ClassA')));
	}

	public function testRegisterType_ResolveCollectionWithKeys1()
	{
		$this->builder->registerType('ClassB')->asSelf()->keyed('key1');
		$this->builder->registerType('ClassB')->asSelf()->keyed('key2');
		$container = $this->builder->build();
		$objects = $container->resolveAll('ClassB');
		$this->assertInstanceOf('ClassB', $objects['key1']);
		$this->assertInstanceOf('ClassB', $objects['key2']);
	}

	public function testRegisterType_ResolveCollectionWithKeys2()
	{
		$this->builder->registerType('ClassB')->asA('ClassA')->keyed('key1');
		$this->builder->registerType('ClassC')->asA('ClassA')->keyed('key2');
		$container = $this->builder->build();
		$objects = $container->resolveAll('ClassA');
		$this->assertInstanceOf('ClassB', $objects['key1']);
		$this->assertInstanceOf('ClassC', $objects['key2']);
	}

	public function testRegisterInstance_ResolveCollectionWithKeys()
	{
		$this->builder->registerInstance(new \ClassE('key1'))->asSelf()->keyed('key1');
		$this->builder->registerInstance(new \ClassE('key2'))->asSelf()->keyed('key2');
		$container = $this->builder->build();
		$objects = $container->resolveAll('ClassE');
		$this->assertInstanceOf('ClassE', $objects['key1']);
		$this->assertInstanceOf('ClassE', $objects['key2']);
		$this->assertEquals('key1', $objects['key1']->value);
		$this->assertEquals('key2', $objects['key2']->value);
	}

	public function testRegister_ResolveCollectionWithKeys()
	{
		$this->builder->register(function() { return new \ClassE('key1'); })->asA('ClassE')->keyed('key1');
		$this->builder->register(function() { return new \ClassE('key2'); })->asA('ClassE')->keyed('key2');
		$container = $this->builder->build();
		$objects = $container->resolveAll('ClassE');
		$this->assertInstanceOf('ClassE', $objects['key1']);
		$this->assertInstanceOf('ClassE', $objects['key2']);
		$this->assertEquals('key1', $objects['key1']->value);
		$this->assertEquals('key2', $objects['key2']->value);
	}

	public function testRegisterType_ResolveUnaryCollection()
	{
		$this->builder->registerType('ClassC')->asA('InterfaceA1');
		$this->builder->registerType('ClassDD');
		$container = $this->builder->build();
		$this->assertInstanceOf('ClassC', $container->resolve('InterfaceA1'));
		$dd = $container->resolve('ClassDD');
		$this->assertTrue(is_array($dd->intf));
		$this->assertEquals(1, count($dd->intf));
		$this->assertInstanceOf('ClassC', $dd->intf[0]);
	}

	public function testRegister_ResolveUnaryCollection()
	{
		$this->builder->register(function() { return new \ClassE('key1'); })->asA('ClassE');
		$container = $this->builder->build();
		$objects = $container->resolveAll('ClassE');
		$this->assertInstanceOf('ClassE', $objects[0]);
		$this->assertEquals('key1', $objects[0]->value);
	}

	public function testRegisterType_ResolveUnaryCollectionWithKey()
	{
		$this->builder->registerType('ClassB')->asA('ClassA')->keyed('key1');
		$container = $this->builder->build();
		$objects = $container->resolveAll('ClassA');
		$this->assertInstanceOf('ClassB', $objects['key1']);
	}

	public function testRegisterType_ResolveEmptyCollection()
	{
		$container = $this->builder->build();
		$objects = $container->resolveAll('ClassA');
		$this->assertTrue(is_array($objects));
		$this->assertTrue(empty($objects));
	}

	public function testRegisterType_ResolveEmptyCollectionParameter()
	{
		$this->builder->registerType('ClassDD');
		$container = $this->builder->build();
		$dd = $container->resolve('ClassDD');
		$this->assertTrue(is_array($dd->intf));
		$this->assertTrue(empty($dd->intf));
	}
}
