<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Gołek
 * Date: 03.11.13
 * Time: 00:33
 */

namespace Mocks;


use DI\IContainerBuilderStorage;

class ContainerBuilderStorage implements IContainerBuilderStorage {

	private $configuration;

	function putConfiguration(array $configuration)
	{
		$this->configuration = $configuration;
	}

	function getConfiguration()
	{
		return $this->configuration;
	}
}