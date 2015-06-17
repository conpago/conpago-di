<?php

namespace Saigon\Conpago\DI;

interface IContainerBuilderStorage
{
	function putConfiguration(array $configuration);
	function getConfiguration();
}
