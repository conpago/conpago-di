<?php

namespace Conpago\DI;

interface IContainerBuilderStorage
{
	function putConfiguration(array $configuration);
	function getConfiguration();
}
