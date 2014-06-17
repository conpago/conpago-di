<?php

namespace DI;

require_once 'DITestCase.php';

class ContainerTest extends DITestCase
{
	public function testRegisterType_ResolveWithParams()
	{
		$this->builder->registerType('ClassK');
		$this->builder->registerType('ClassA');
		$container = $this->builder->build();
		$classK = $container->resolve('ClassK');
		$this->assertInstanceOf('ClassK', $classK);
		$this->assertInstanceOf('ClassA', $classK->classA);
	}

	public function testRegisterType_ResolveWithMultiParams()
	{
		$this->builder->registerType('ClassL')->asA('ClassK');
		$this->builder->registerType('ClassB')->asBases();
		$this->builder->registerType('ClassD');
		$container = $this->builder->build();
		$classL = $container->resolve('ClassK');
		$this->assertInstanceOf('ClassL', $classL);
		$this->assertInstanceOf('ClassB', $classL->classA);
		$this->assertInstanceOf('ClassD', $classL->classD);
	}

	public function testRegisterType_ResolveWithDescribedParam()
	{
		$this->builder->registerType('ClassM')->asA('ClassK');
		$this->builder->registerType('ClassB')->asBases();
		$this->builder->registerType('ClassD');
		$container = $this->builder->build();
		$classM = $container->resolve('ClassK');
		$this->assertInstanceOf('ClassM', $classM);
		$this->assertInstanceOf('ClassB', $classM->classA);
		$this->assertInstanceOf('ClassD', $classM->classD);
	}

	public function testRegisterType_ResolveWithFactoryParam()
	{
		$this->builder->registerType('ClassP');
		$this->builder->registerType('ClassB')->asBases();
		$container = $this->builder->build();
		$classP = $container->resolve('ClassP');
		$this->assertInstanceOf('ClassP', $classP);
		$this->assertInstanceOf('ClassB', $classP->getClassA());
	}

	public function testRegisterType_ResolveWithParameterizedFactoryParam()
	{
		$this->builder->registerType('ClassA');
		$this->builder->registerType('ClassB');
		$this->builder->registerType('ClassQ');
		$this->builder->registerType('ClassG');
		$container = $this->builder->build();
		$classQ = $container->resolve('ClassQ');
		$this->assertInstanceOf('ClassQ', $classQ);
		$classG = $classQ->getClassG('test', 'test2');
		$this->assertInstanceOf('ClassG', $classG);
		$this->assertEquals('test', $classG->value);
		$this->assertEquals('test2', $classG->value2);
	}


	public function testRegisterType_ResolveWithLazyParams()
	{
		$this->builder->registerType('ClassH')->asBases()->singleInstance();
		$this->builder->registerType('ClassR');
		$container = $this->builder->build();
		$classR = $container->resolve('ClassR');
		$this->assertEquals(0, \ClassD::$instances);
		$this->assertInstanceOf('ClassH', $classR->getClassD());
		$this->assertEquals(1, \ClassD::$instances);
		$classR = $container->resolve('ClassR');
		$this->assertInstanceOf('ClassH', $classR->getClassD());
		$this->assertEquals(1, \ClassD::$instances);
	}

	public function testRegisterType_ResolveLazyCreatesOneInstance()
	{
		$this->builder->registerType('ClassX');
		$this->builder->registerType('ClassY');
		$container = $this->builder->build();
		$classX = $container->resolve('ClassX');
		$classY1 = $classX->getClassY();
		$classY2 = $classX->getClassY();
		$this->assertSame($classY1, $classY2);
	}

	public function testRegisterType_ResolveLazyWithClosure()
	{
		$this->builder->register(function() { return new \ClassD; })->asA('ClassD');
		$this->builder->registerType('ClassR');
		$container = $this->builder->build();
		$classR = $container->resolve('ClassR');
		$this->assertEquals(0, \ClassD::$instances);
		$this->assertInstanceOf('ClassD', $classR->getClassD());
		$this->assertEquals(1, \ClassD::$instances);
		$this->assertInstanceOf('ClassD', $classR->getClassD());
		$this->assertEquals(1, \ClassD::$instances);
	}

	public function testResolveType_ResolveCircularWithLazy()
	{
		$this->builder->registerType('ClassX')->singleInstance();
		$this->builder->registerType('ClassY');
		$container = $this->builder->build();
		$classX = $container->resolve('ClassX');
		$this->assertInstanceOf('ClassX', $classX);
		$classY = $classX->getClassY();
		$this->assertInstanceOf('ClassY', $classY);
		$this->assertSame($classX, $classY->getClassX());
	}

	public function testRegisterType_ResolveWithParam()
	{
		$this->builder->registerType('ClassB')->asSelf()->asA('ClassA');
		$this->builder->registerType('ClassG');
		$classG = $this->builder->build()->resolve('ClassG', Parameter::def(), 'test', Parameter::def(), 'test2');
		$this->assertInstanceOf('ClassA', $classG->classA);
		$this->assertEquals('test', $classG->value);
		$this->assertInstanceOf('ClassB', $classG->classB);
		$this->assertEquals('test2', $classG->value2);
	}

	/**
	 * @expectedException \DI\Exceptions\MultipleBuilderUsesException
	 */
	public function testMultipleBuilds_Fail()
	{
		$this->builder->registerType('ClassB')->asInterfaces();
		$this->assertInstOf('ClassB', 'InterfaceA1');
		$this->builder->registerType('ClassB');
	}

	public function testRegister_ResolveWithContainerAndParams()
	{
		$this->builder->registerType('ClassA');
		$this->builder->register(
			function(IContainer $c, $v) { return new \ClassN($v, $c->resolve('ClassA')); })
			->asA('ClassN');
		$classN = $this->builder->build()->resolve('ClassN', 'test');
		$this->assertEquals('test', $classN->value);
		$this->assertInstanceOf('ClassA', $classN->classA);
	}

	public function testRegisterTypeNamedSingleton()
	{
		$this->builder->registerType('ClassD')->singleInstance()->named('test');
		$container = $this->builder->build();
		$classD = $container->resolveNamed('test');
		$this->assertSame($classD, $container->resolveNamed('test'));
		$this->assertEquals(1, \ClassD::$instances);
	}

	/**
	 * @expectedException \DI\Exceptions\MissingParameterException
	 */
	public function testMissingParameter_Fail()
	{
		$this->builder->registerType('ClassB')->asSelf()->asA('ClassA');
		$this->builder->registerType('ClassG');
		$this->builder->build()->resolve('ClassG', 'abc');
	}

	public function testResolvingDefaultParameters()
	{
		$this->builder->registerType('ClassE');
		$this->builder->registerType('ClassZ');
		$container = $this->builder->build();
		$z = $container->resolve('ClassZ', new \ClassE('test'), 'bla', Parameter::def());
		$this->assertEquals('test', $z->e1->value);
		$this->assertEquals('default', $z->e2->value);
		$this->assertEquals('bla', $z->value);
		$z = $container->resolve('ClassZ', Parameter::def(), 'bla', new \ClassE('test'));
		$this->assertEquals('test', $z->e2->value);
		$this->assertEquals('default', $z->e1->value);
		$this->assertEquals('bla', $z->value);
	}

	public function testResolvingExplicitParameters()
	{
		$this->builder->registerType('ClassE')->withParams('test1')->named('test1');
		$this->builder->registerType('ClassE')->withParams('test2')->named('test2');
		$container = $this->builder->build();
		$this->assertEquals('test1', $container->resolveNamed('test1')->value);
		$this->assertEquals('test2', $container->resolveNamed('test2')->value);
	}

	public function testResolvingFactoryParameters()
	{
		$this->builder->registerType('ClassE')->withParams('test1');
		$this->builder->registerType('ClassZ');
		$this->builder->registerType('ClassZZ');
		$zz = $this->builder->build()->resolve('ClassZZ');
		$this->assertEquals('test1', $zz->getZ1(new \ClassE)->e1->value);
		$this->assertEquals('test1', $zz->getZ2(new \ClassE)->e2->value);
		$this->assertEquals('test2', $zz->getZ2(new \ClassE('test2'))->e1->value);
	}

	public function testResolvingNamedParameters()
	{
		$this->builder->registerType('ClassZ')->withParams(Parameter::def(), 'z', Parameter::named('test2'));
		$this->builder->registerType('ClassE')->withParams('test1');
		$this->builder->registerType('ClassE')->withParams('test2')->named('test2');
		$container = $this->builder->build();
		$z = $container->resolve('ClassZ');
		$this->assertEquals('test1', $container->resolve('ClassE')->value);
		$this->assertEquals('test2', $container->resolveNamed('test2')->value);
		$this->assertEquals('test1', $z->e1->value);
		$this->assertEquals('z', $z->value);
		$this->assertEquals('test2', $z->e2->value);
	}

	public function testResolvingCollectionOfFactories()
	{
		$this->builder->registerType('ClassA')->asA('ClassA')->keyed('a');
		$this->builder->registerType('ClassB')->asA('ClassA')->keyed('b');
		$this->builder->registerType('ClassAA');
		$this->container = $this->builder->build();
		$aa = $this->container->resolve('ClassAA');
		$this->assertInstanceOf('ClassA', $aa->getA());
		$this->assertInstanceOf('ClassB', $aa->getB());
		$this->assertNotInstanceOf('ClassB', $aa->getA());
	}

	public function testResolvingCollectionOfLazies()
	{
		$this->builder->registerType('ClassA')->asA('ClassA')->keyed('a');
		$this->builder->registerType('ClassB')->asA('ClassA')->keyed('b');
		$this->builder->registerType('ClassAB');
		$this->container = $this->builder->build();
		$ab = $this->container->resolve('ClassAB');
		$a = $ab->getA();
		$b = $ab->getB();
		$this->assertInstanceOf('ClassA', $a);
		$this->assertInstanceOf('ClassB', $b);
		$this->assertNotInstanceOf('ClassB', $a);
		$this->assertSame($a, $ab->getA());
		$this->assertSame($b, $ab->getB());
	}

	public function testParsingLazySpecification()
	{
		$this->builder->registerType('ClassA');
		$this->builder->registerType('ClassBA');
		$this->container = $this->builder->build();
		$ba = $this->container->resolve('ClassBA');
		$this->assertInstanceOf('ClassA', $ba->getA());
	}

	public function testParsingFactorySpecification()
	{
		$this->builder->registerType('ClassA');
		$this->builder->registerType('ClassBB');
		$this->container = $this->builder->build();
		$bb = $this->container->resolve('ClassBB');
		$this->assertInstanceOf('ClassA', $bb->getA());
	}

	public function testParsingLazyArraySpecification()
	{
		$this->builder->registerType('ClassA')->asA('ClassA')->keyed('a');
		$this->builder->registerType('ClassB')->asA('ClassA')->keyed('b');
		$this->builder->registerType('ClassBC');
		$this->container = $this->builder->build();
		$bc = $this->container->resolve('ClassBC');
		$this->assertInstanceOf('ClassA', $bc->getA());
		$this->assertInstanceOf('ClassB', $bc->getB());
	}

	public function testParsingFactoryArraySpecification()
	{
		$this->builder->registerType('ClassA')->asA('ClassA')->keyed('a');
		$this->builder->registerType('ClassB')->asA('ClassA')->keyed('b');
		$this->builder->registerType('ClassBD');
		$this->container = $this->builder->build();
		$bc = $this->container->resolve('ClassBD');
		$this->assertInstanceOf('ClassA', $bc->getA());
		$this->assertInstanceOf('ClassB', $bc->getB());
	}

	public function test_ContainerContainsSelf()
	{
		$this->assertSame($this->getContainer(), $this->resolve('DI\\IContainer'));
	}
}
