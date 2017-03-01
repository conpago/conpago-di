<?php

namespace Conpago\DI;

use ClassA;
use ClassAA;
use ClassAB;
use ClassB;
use ClassBA;
use ClassBB;
use ClassBC;
use ClassBD;
use ClassD;
use ClassE;
use ClassG;
use ClassH;
use ClassK;
use ClassL;
use ClassM;
use ClassN;
use ClassP;
use ClassQ;
use ClassR;
use ClassX;
use ClassY;
use ClassZ;
use ClassZZ;
use Conpago\DI\Exceptions\MissingParameterException;
use Conpago\DI\Exceptions\MultipleBuilderUsesException;
use InterfaceA1;

require_once 'DITestCase.php';

class ContainerTest extends DITestCase
{
	public function testRegisterType_ResolveWithParams()
	{
		$this->builder->registerType(ClassK::class);
		$this->builder->registerType(ClassA::class);
		$container = $this->builder->build();
		$classK = $container->resolve(ClassK::class);
		$this->assertInstanceOf(ClassK::class, $classK);
		$this->assertInstanceOf(ClassA::class, $classK->classA);
	}

    /**
     *
     */
    public function testRegisterType_ResolveWithMultiParams()
	{
		$this->builder->registerType(ClassL::class)->asA(ClassK::class);
		$this->builder->registerType(ClassB::class)->asBases();
		$this->builder->registerType(ClassD::class);
		$container = $this->builder->build();
		$classL = $container->resolve(ClassK::class);
		$this->assertInstanceOf(ClassL::class, $classL);
		$this->assertInstanceOf(ClassB::class, $classL->classA);
		$this->assertInstanceOf(ClassD::class, $classL->classD);
	}

	public function testRegisterType_ResolveWithDescribedParam()
	{
		$this->builder->registerType(ClassM::class)->asA(ClassK::class);
		$this->builder->registerType(ClassB::class)->asBases();
		$this->builder->registerType(ClassD::class);
		$container = $this->builder->build();
		$classM = $container->resolve(ClassK::class);
		$this->assertInstanceOf(ClassM::class, $classM);
		$this->assertInstanceOf(ClassB::class, $classM->classA);
		$this->assertInstanceOf(ClassD::class, $classM->classD);
	}

	public function testRegisterType_ResolveWithFactoryParam()
	{
		$this->builder->registerType(ClassP::class);
		$this->builder->registerType(ClassB::class)->asBases();
		$container = $this->builder->build();

		/** @var ClassP $classP */
		$classP = $container->resolve(ClassP::class);
		$this->assertInstanceOf(ClassP::class, $classP);
		$this->assertInstanceOf(ClassB::class, $classP->getClassA());
	}

	public function testRegisterType_ResolveWithParameterizedFactoryParam()
	{
		$this->builder->registerType(ClassA::class);
		$this->builder->registerType(ClassB::class);
		$this->builder->registerType(ClassQ::class);
		$this->builder->registerType(ClassG::class);
		$container = $this->builder->build();

		/** @var ClassQ $classQ */
		$classQ = $container->resolve(ClassQ::class);
		$this->assertInstanceOf(ClassQ::class, $classQ);
		$classG = $classQ->getClassG('test', 'test2');
		$this->assertInstanceOf(ClassG::class, $classG);
		$this->assertEquals('test', $classG->value);
		$this->assertEquals('test2', $classG->value2);
	}


	public function testRegisterType_ResolveWithLazyParams()
	{
		$this->builder->registerType(ClassH::class)->asBases()->singleInstance();
		$this->builder->registerType(ClassR::class);
		$container = $this->builder->build();
		/** @var ClassR $classR */
		$classR = $container->resolve(ClassR::class);
		$this->assertEquals(0, ClassD::$instances);
		$this->assertInstanceOf(ClassH::class, $classR->getClassD());
		$this->assertEquals(1, ClassD::$instances);
		$classR = $container->resolve(ClassR::class);
		$this->assertInstanceOf(ClassH::class, $classR->getClassD());
		$this->assertEquals(1, ClassD::$instances);
	}

	public function testRegisterType_ResolveLazyCreatesOneInstance()
	{
		$this->builder->registerType(ClassX::class);
		$this->builder->registerType(ClassY::class);
		$container = $this->builder->build();
		$classX = $container->resolve(ClassX::class);
		$classY1 = $classX->getClassY();
		$classY2 = $classX->getClassY();
		$this->assertSame($classY1, $classY2);
	}

	public function testRegisterType_ResolveLazyWithClosure()
	{
		$this->builder->register(function() { return new ClassD; })->asA(ClassD::class);
		$this->builder->registerType(ClassR::class);
		$container = $this->builder->build();
		$classR = $container->resolve(ClassR::class);
		$this->assertEquals(0, ClassD::$instances);
		$this->assertInstanceOf(ClassD::class, $classR->getClassD());
		$this->assertEquals(1, ClassD::$instances);
		$this->assertInstanceOf(ClassD::class, $classR->getClassD());
		$this->assertEquals(1, ClassD::$instances);
	}

	public function testResolveType_ResolveCircularWithLazy()
	{
		$this->builder->registerType(ClassX::class)->singleInstance();
		$this->builder->registerType(ClassY::class);
		$container = $this->builder->build();
		$classX = $container->resolve(ClassX::class);
		$this->assertInstanceOf(ClassX::class, $classX);
		$classY = $classX->getClassY();
		$this->assertInstanceOf(ClassY::class, $classY);
		$this->assertSame($classX, $classY->getClassX());
	}

	public function testRegisterType_ResolveWithParam()
	{
		$this->builder->registerType(ClassB::class)->asSelf()->asA(ClassA::class);
		$this->builder->registerType(ClassG::class);
		$classG = $this->builder->build()->resolve(ClassG::class, Parameter::def(), 'test', Parameter::def(), 'test2');
		$this->assertInstanceOf(ClassA::class, $classG->classA);
		$this->assertEquals('test', $classG->value);
		$this->assertInstanceOf(ClassB::class, $classG->classB);
		$this->assertEquals('test2', $classG->value2);
	}

	public function testMultipleBuilds_Fail()
	{
	    $this->expectException(MultipleBuilderUsesException::class);

		$this->builder->registerType(ClassB::class)->asInterfaces();
		$this->assertInstOf(ClassB::class, InterfaceA1::class);
		$this->builder->registerType(ClassB::class);
	}

	public function testRegister_ResolveWithContainerAndParams()
	{
		$this->builder->registerType(ClassA::class);
		$this->builder->register(
			function(IContainer $c, $v) { return new ClassN($v, $c->resolve(ClassA::class)); })
			->asA(ClassN::class);
		$classN = $this->builder->build()->resolve(ClassN::class, 'test');
		$this->assertEquals('test', $classN->value);
		$this->assertInstanceOf(ClassA::class, $classN->classA);
	}

	public function testRegisterTypeNamedSingleton()
	{
		$this->builder->registerType(ClassD::class)->singleInstance()->named('test');
		$container = $this->builder->build();
		$classD = $container->resolveNamed('test');
		$this->assertSame($classD, $container->resolveNamed('test'));
		$this->assertEquals(1, ClassD::$instances);
	}

	public function testMissingParameter_Fail()
	{
	    $this->expectException(MissingParameterException::class);

		$this->builder->registerType(ClassB::class)->asSelf()->asA(ClassA::class);
		$this->builder->registerType(ClassG::class);
		$this->builder->build()->resolve(ClassG::class, 'abc');
	}

	public function testResolvingDefaultParameters()
	{
		$this->builder->registerType(ClassE::class);
		$this->builder->registerType(ClassZ::class);
		$container = $this->builder->build();
		$z = $container->resolve(ClassZ::class, new ClassE('test'), 'bla', Parameter::def());
		$this->assertEquals('test', $z->e1->value);
		$this->assertEquals('default', $z->e2->value);
		$this->assertEquals('bla', $z->value);
		$z = $container->resolve(ClassZ::class, Parameter::def(), 'bla', new ClassE('test'));
		$this->assertEquals('test', $z->e2->value);
		$this->assertEquals('default', $z->e1->value);
		$this->assertEquals('bla', $z->value);
	}

	public function testResolvingExplicitParameters()
	{
		$this->builder->registerType(ClassE::class)->withParams('test1')->named('test1');
		$this->builder->registerType(ClassE::class)->withParams('test2')->named('test2');
		$container = $this->builder->build();
		$this->assertEquals('test1', $container->resolveNamed('test1')->value);
		$this->assertEquals('test2', $container->resolveNamed('test2')->value);
	}

	public function testResolvingFactoryParameters()
	{
		$this->builder->registerType(ClassE::class)->withParams('test1');
		$this->builder->registerType(ClassZ::class);
		$this->builder->registerType(ClassZZ::class);
		/** @var ClassZZ $zz */
		$zz = $this->builder->build()->resolve(ClassZZ::class);
		$this->assertEquals('test1', $zz->getZ1(new ClassE)->e1->value);
		$this->assertEquals('test1', $zz->getZ2(new ClassE)->e2->value);
		$this->assertEquals('test2', $zz->getZ2(new ClassE('test2'))->e1->value);
	}

	public function testResolvingNamedParameters()
	{
		$this->builder->registerType(ClassZ::class)->withParams(Parameter::def(), 'z', Parameter::named('test2'));
		$this->builder->registerType(ClassE::class)->withParams('test1');
		$this->builder->registerType(ClassE::class)->withParams('test2')->named('test2');
		$container = $this->builder->build();
		$z = $container->resolve(ClassZ::class);
		$this->assertEquals('test1', $container->resolve(ClassE::class)->value);
		$this->assertEquals('test2', $container->resolveNamed('test2')->value);
		$this->assertEquals('test1', $z->e1->value);
		$this->assertEquals('z', $z->value);
		$this->assertEquals('test2', $z->e2->value);
	}

	public function testResolvingCollectionOfFactories()
	{
		$this->builder->registerType(ClassA::class)->asA(ClassA::class)->keyed('a');
		$this->builder->registerType(ClassB::class)->asA(ClassA::class)->keyed('b');
		$this->builder->registerType(ClassAA::class);
		$this->container = $this->builder->build();
		$aa = $this->container->resolve(ClassAA::class);
		$this->assertInstanceOf(ClassA::class, $aa->getA());
		$this->assertInstanceOf(ClassB::class, $aa->getB());
		$this->assertNotInstanceOf(ClassB::class, $aa->getA());
	}

	public function testResolvingCollectionOfLazies()
	{
		$this->builder->registerType(ClassA::class)->asA(ClassA::class)->keyed('a');
		$this->builder->registerType(ClassB::class)->asA(ClassA::class)->keyed('b');
		$this->builder->registerType(ClassAB::class);
		$this->container = $this->builder->build();
		$ab = $this->container->resolve(ClassAB::class);
		$a = $ab->getA();
		$b = $ab->getB();
		$this->assertInstanceOf(ClassA::class, $a);
		$this->assertInstanceOf(ClassB::class, $b);
		$this->assertNotInstanceOf(ClassB::class, $a);
		$this->assertSame($a, $ab->getA());
		$this->assertSame($b, $ab->getB());
	}

	public function testParsingLazySpecification()
	{
		$this->builder->registerType(ClassA::class);
		$this->builder->registerType(ClassBA::class);
		$this->container = $this->builder->build();
		$ba = $this->container->resolve(ClassBA::class);
		$this->assertInstanceOf(ClassA::class, $ba->getA());
	}

	public function testParsingFactorySpecification()
	{
		$this->builder->registerType(ClassA::class);
		$this->builder->registerType(ClassBB::class);
		$this->container = $this->builder->build();
		$bb = $this->container->resolve(ClassBB::class);
		$this->assertInstanceOf(ClassA::class, $bb->getA());
	}

	public function testParsingLazyArraySpecification()
	{
		$this->builder->registerType(ClassA::class)->asA(ClassA::class)->keyed('a');
		$this->builder->registerType(ClassB::class)->asA(ClassA::class)->keyed('b');
		$this->builder->registerType(ClassBC::class);
		$this->container = $this->builder->build();
		$bc = $this->container->resolve(ClassBC::class);
		$this->assertInstanceOf(ClassA::class, $bc->getA());
		$this->assertInstanceOf(ClassB::class, $bc->getB());
	}

	public function testParsingFactoryArraySpecification()
	{
		$this->builder->registerType(ClassA::class)->asA(ClassA::class)->keyed('a');
		$this->builder->registerType(ClassB::class)->asA(ClassA::class)->keyed('b');
		$this->builder->registerType(ClassBD::class);
		$this->container = $this->builder->build();
		$bc = $this->container->resolve(ClassBD::class);
		$this->assertInstanceOf(ClassA::class, $bc->getA());
		$this->assertInstanceOf(ClassB::class, $bc->getB());
	}

	public function test_ContainerContainsSelf()
	{
		$this->assertSame($this->getContainer(), $this->resolve(IContainer::class));
	}
}
