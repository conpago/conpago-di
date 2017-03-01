<?php

namespace Conpago\DI;

use PHPUnit\Framework\TestCase;

require_once realpath('tests/Conpago/DI/helpers/Classes.php');
require_once realpath('tests/Conpago/DI/helpers/ClassesInTestNamespace.php');
require_once realpath('tests/Conpago/DI/helpers/ClassesInTestSubNamespace.php');

abstract class DITestCase extends TestCase
{
	/**
	 * @var IContainerBuilder
	 */
	protected $builder;
	/**
	 * @var IContainer
	 */
	protected $container;

	public function setUp()
	{
		$this->builder = new ContainerBuilder;
		\ClassD::$instances = 0;
	}

	/**
	 * @param $id
	 *
	 * @return ITypeRegisterer
	 */
	protected function registerType($id)
	{
		return $this->builder->registerType($id);
	}

	/**
	 * @param $function
	 *
	 * @return IClosureRegisterer
	 */
	protected function register($function)
	{
		return $this->builder->register($function);
	}

	/**
	 * @return IInstanceRegisterer
	 */
	protected function registerInstance($instance)
	{
		return $this->builder->registerInstance($instance);
	}

	protected function assertInstOf($targetClass, $sourceClass)
	{
		$this->assertInstanceOf($targetClass, $this->resolve($sourceClass));
	}

	protected function resolve($id)
	{
		return $this->getContainer()->resolve($id);
	}

	protected function resolveAll($id)
	{
		return $this->getContainer()->resolveAll($id);
	}

	protected function resolveNamed($name)
	{
		return $this->getContainer()->resolveNamed($name);
	}

	/**
	 * @var IContainer
	 * @return \Conpago\DI\IContainer
	 */
	protected function getContainer()
	{
		if (!$this->container)
			$this->container = $this->builder->build();
		return $this->container;
	}
}
