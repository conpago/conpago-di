<?php

namespace Saigon\Conpago\DI;

/**
 * Class ContainerBuilderPersister
 * Represents persister of IPersistableContainerBuilder
 *
 * @package DI
 */
class ContainerBuilderPersister implements IContainerBuilderPersister
{
	/**
	 * Creates new instance of ContainerBuilderPersister
	 *
	 * @param IContainerBuilderStorage $storage
	 */
	public function __construct(IContainerBuilderStorage $storage)
	{
		$this->storage = $storage;
	}

	/**
	 * Saves builder into storage
	 *
	 * @param IPersistableContainerBuilder $builder
	 */
	public function saveContainerBuilder(IPersistableContainerBuilder $builder)
	{
		$configuration = $builder->getConfiguration();
		$asArray = $this->convertConfigurationToArray($configuration);
		$this->storage->putConfiguration($asArray);
	}

	/**
	 * loads builder from storage
	 *
	 * @return ContainerBuilder|IContainerBuilder
	 */
	public function loadContainerBuilder()
	{
		$asArray = $this->storage->getConfiguration();
		$configuration = $this->convertArrayToConfiguration($asArray);
		return new ContainerBuilder($configuration);
	}

	private function convertConfigurationToArray($configuration)
	{
		foreach ($configuration as &$component)
			$component = $this->convertComponentToArray($component);
		return $configuration;
	}

	private function convertComponentToArray(Registerers\IRegisterer $component)
	{
		return Serializers\Serializer::getInstance()->toArray($component);
	}

	private function convertArrayToConfiguration($configuration)
	{
		foreach ($configuration as &$component)
			$component = $this->convertArrayToComponent($component);
		return $configuration;
	}

	private function convertArrayToComponent($configuration)
	{
		return Serializers\Serializer::getInstance()->fromArray($configuration);
	}

	/**
	 * @var IContainerBuilderStorage
	 */
	private $storage;
}
