<?php

namespace Saigon\Conpago\DI;

use Mocks\ContainerBuilderStorage;

require_once 'tests/Saigon/Conpago/DI/mocks/ContainerBuilderStorage.php';
require_once 'tests/Saigon/Conpago/DI/helpers/Classes.php';

class ContainerBuilderPersisterTest extends \PHPUnit_Framework_TestCase
{
	private $builder;
	private $container;

	public function setUp()
	{
		$this->builder = new ContainerBuilder;
	}

	private function exchange()
	{
		$storage = new ContainerBuilderStorage();
		$persister = new ContainerBuilderPersister($storage);
		$persister->saveContainerBuilder($this->builder);

		$persister = new ContainerBuilderPersister($storage);
		$builder = $persister->loadContainerBuilder();

		$this->container = $builder->build();
	}

	public function test_SimpleType()
	{
		$this->builder->registerType('ClassC');
		$this->exchange();
		$this->assertInstanceOf('ClassC', $this->container->resolve('ClassC'));
	}

	public function test_TypeWithAlias()
	{
		$this->builder->registerType('ClassC')->asA('ClassB');
		$this->exchange();
		$this->assertInstanceOf('ClassC', $this->container->resolve('ClassB'));
	}

	public function test_TypeNamed()
	{
		$this->builder->registerType('ClassE')->withParams('e1')->named('e1')->named('e11');
		$this->builder->registerType('ClassE')->withParams('e2')->named('e2');
		$this->exchange();
		$this->assertEquals('e1', $this->container->resolveNamed('e1')->value);
		$this->assertEquals('e1', $this->container->resolveNamed('e11')->value);
		$this->assertEquals('e2', $this->container->resolveNamed('e2')->value);
	}

	public function test_TypeKeyed()
	{
		$this->builder->registerType('ClassE2')->asA('ClassE2')->withParams('f1')->keyed('f1');
		$this->builder->registerType('ClassE2')->asA('ClassE2')->withParams('f2')->keyed('f2');
		$this->exchange();
		$fs = $this->container->resolveAll('ClassE2');
		$this->assertEquals('f1', $fs['f1']->value);
		$this->assertEquals('f2', $fs['f2']->value);
	}

	public function test_TypeAsBases()
	{
		$this->builder->registerType('ClassB')->asBases();
		$this->exchange();
		$this->assertInstanceOf('ClassB', $this->container->resolve('ClassA'));
	}

	public function test_TypeAsInterfaces()
	{
		$this->builder->registerType('ClassA')->asInterfaces();
		$this->exchange();
		$this->assertInstanceOf('ClassA', $this->container->resolve('InterfaceA1'));
	}

	public function test_TypeAsSelf()
	{
		$this->builder->registerType('ClassB')->asBases()->asSelf();
		$this->exchange();
		$this->assertInstanceOf('ClassB', $this->container->resolve('ClassB'));
	}

	public function test_TypeWithDefaultParam1()
	{
		$this->builder->registerType('ClassE')->named('f1');
		$this->exchange();
		$f = $this->container->resolveNamed('f1');
		$this->assertEquals('default', $f->value);
	}

	public function test_TypeWithDefaultParam2()
	{
		$this->builder->registerType('ClassE')->named('f1')->withParams(Parameter::def());
		$this->exchange();
		$f = $this->container->resolveNamed('f1');
		$this->assertEquals('default', $f->value);
	}

	public function test_TypeWithParamValue()
	{
		$this->builder->registerType('ClassE2')->named('f1')->withParams('f1');
		$this->exchange();
		$f = $this->container->resolveNamed('f1');
		$this->assertEquals('f1', $f->value);
	}

	public function test_TypeWithNamedParam()
	{
		$this->builder->registerType('ClassE2')->withParams('f1')->named('f1');
		$this->builder->registerType('ClassE2')->withParams(Parameter::named('f1'))->named('f2');
		$this->exchange();
		$f = $this->container->resolveNamed('f2');
		$this->assertEquals('f1', $f->value->getName());
	}

	public function test_TypeSingleton()
	{
		$this->builder->registerType('ClassD')->singleInstance();
		$this->exchange();
		$d = $this->container->resolve('ClassD');
		$this->assertSame($d, $this->container->resolve('ClassD'));
	}

	public function test_SimpleInstance()
	{
		$this->builder->registerInstance(new \ClassE('e'));
		$this->exchange();
		$e = $this->container->resolve('ClassE');
		$this->assertInstanceOf('ClassE', $e);
		$this->assertSame($e, $this->container->resolve('ClassE'));
		$this->assertEquals('e', $e->value);
	}

	public function test_InstanceWithAlias()
	{
		$this->builder->registerInstance(new \ClassE2('e2'))->asA('ClassE');
		$this->exchange();
		$e = $this->container->resolve('ClassE');
		$this->assertInstanceOf('ClassE2', $e);
		$this->assertEquals('e2', $e->value);
	}

	public function test_InstanceNamed()
	{
		$this->builder->registerInstance(new \ClassE('e1'))->named('e1')->named('e11');
		$this->builder->registerInstance(new \ClassE('e2'))->named('e2');
		$this->exchange();
		$this->assertEquals('e1', $this->container->resolveNamed('e1')->value);
		$this->assertEquals('e1', $this->container->resolveNamed('e11')->value);
		$this->assertEquals('e2', $this->container->resolveNamed('e2')->value);
	}

	public function test_InstanceKeyed()
	{
		$this->builder->registerInstance(new \ClassE('e1'))->asA('ClassE')->keyed('e1');
		$this->builder->registerInstance(new \ClassE('e2'))->asA('ClassE')->keyed('e2');
		$this->exchange();
		$es = $this->container->resolveAll('ClassE');
		$this->assertEquals('e1', $es['e1']->value);
		$this->assertEquals('e2', $es['e2']->value);
	}

	public function test_InstanceAsSelf()
	{
		$this->builder->registerInstance(new \ClassE('e'))->asSelf()->named('e');
		$this->exchange();
		$this->assertInstanceOf('ClassE', $this->container->resolve('ClassE'));
	}

	public function test_SimpleClosure()
	{
		$this->builder->register('return new \\ClassA')->asA('ClassA');
		$this->exchange();
		$this->assertInstanceOf('ClassA', $this->container->resolve('ClassA'));
	}

	/**
	 * @expectedException \Saigon\Conpago\DI\Exceptions\SerializingClosureException
	 */
	public function test_FunctionClosure_Fail()
	{
		$this->builder->register(function() { return new \ClassA; })->asA('ClassA');
		$this->exchange();
	}

	public function test_ClosureWithAlias()
	{
		$this->builder->register('return new \ClassE2(\'e2\')')->asA('ClassE');
		$this->exchange();
		$e = $this->container->resolve('ClassE');
		$this->assertInstanceOf('ClassE2', $e);
		$this->assertEquals('e2', $e->value);
	}

	public function test_ClosureNamed()
	{
		$this->builder->register('return new \ClassE(\'e1\')')->named('e1')->named('e11');
		$this->builder->register('return new \ClassE(\'e2\')')->named('e2');
		$this->exchange();
		$this->assertEquals('e1', $this->container->resolveNamed('e1')->value);
		$this->assertEquals('e1', $this->container->resolveNamed('e11')->value);
		$this->assertEquals('e2', $this->container->resolveNamed('e2')->value);
	}

	public function test_ClosureKeyed()
	{
		$this->builder->register('return new \ClassE(\'e1\')')->asA('ClassE')->keyed('e1');
		$this->builder->register('return new \ClassE(\'e2\')')->asA('ClassE')->keyed('e2');
		$this->exchange();
		$es = $this->container->resolveAll('ClassE');
		$this->assertEquals('e1', $es['e1']->value);
		$this->assertEquals('e2', $es['e2']->value);
	}

	public function test_ClosureSingleton()
	{
		$this->builder->register('return new \ClassD')->asA('ClassD')->singleInstance();
		$this->exchange();
		$d = $this->container->resolve('ClassD');
		$this->assertSame($d, $this->container->resolve('ClassD'));
	}
}
