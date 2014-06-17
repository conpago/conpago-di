<?php

namespace DI;

require_once 'tests/core/di/helpers/Classes.php';
require_once 'tests/core/di//helpers/ClassesInTestNamespace.php';
require_once 'tests/core/di//helpers/ClassesInTestSubNamespace.php';

abstract class DITestCase extends \PHPUnit_Framework_TestCase
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
		$this->builder->registerType('')->onActivated()
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
	 * @return \DI\IContainer
	 */
	protected function getContainer()
	{
		if (!$this->container)
			$this->container = $this->builder->build();
		return $this->container;
	}
}