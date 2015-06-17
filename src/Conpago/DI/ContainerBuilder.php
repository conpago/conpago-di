<?php

namespace Saigon\Conpago\DI;

use Saigon\Conpago\DI\Implementation\TypeRepository;
use Saigon\Conpago\DI\Implementation\TypeRepositoryBuilder;
use Saigon\Conpago\DI\Registerers\ClosureRegisterer;
use Saigon\Conpago\DI\Registerers\InstanceRegisterer;
use Saigon\Conpago\DI\Registerers\TypeRegisterer;

/**
 * Class ContainerBuilder
 * Represents a container builder
 *
 * @package DI
 */
class ContainerBuilder implements IContainerBuilder, IPersistableContainerBuilder
{
	/**
	 * Creates new instance of ContainerBuilder
	 *
	 * @param array $configuration
	 */
	public function __construct($configuration = array())
	{
		$this->registerers = $configuration;
		$this->registerBackreferenceToContainer();
	}

	/**
	 * Register instance of class in container
	 *
	 * @param $instance
	 *
	 * @return InstanceRegisterer
	 */
	public function registerInstance($instance)
	{
		$this->checkForMultipleUse();
		return $this->registerers[] = new Registerers\InstanceRegisterer($instance);
	}

	/**
	 * Register type in container
	 *
	 * @param $typeName
	 *
	 * @return TypeRegisterer
	 */
	public function registerType($typeName)
	{
		$this->checkForMultipleUse();
		return $this->registerers[] = new Registerers\TypeRegisterer($typeName);
	}

	/**
	 * Register closure in container
	 *
	 * @param $closure
	 *
	 * @return ClosureRegisterer
	 */
	public function register($closure)
	{
		$this->checkForMultipleUse();
		return $this->registerers[] = new Registerers\ClosureRegisterer($closure);
	}

	/**
	 * Build container
	 *
	 * @return IContainer
	 */
	public function build()
	{
		$this->checkForMultipleUse();

		$repository = new TypeRepository($this->buildRegistry());
		$result = $repository->getContainer();
		$this->containerBuilt = true;
		return $result;
	}

	/**
	 * Return current configuration of container
	 *
	 * @return array
	 */
	public function getConfiguration()
	{
		return $this->registerers;
	}

	private function registerBackreferenceToContainer()
	{
		$this->register('return $container;')->asA('Saigon\Conpago\DI\IContainer');
	}

	private function checkForMultipleUse()
	{
		if ($this->containerBuilt)
			throw new Exceptions\MultipleBuilderUsesException;
	}

	private function buildRegistry()
	{
		$builder = new TypeRepositoryBuilder($this->registerers);
		return $builder->build();
	}

	private $containerBuilt = false;
	private $registerers = array();
}
