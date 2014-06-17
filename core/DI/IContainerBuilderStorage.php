<?php

namespace DI;

interface IContainerBuilderStorage
{
	function putConfiguration(array $configuration);
	function getConfiguration();
}
