<?php

namespace Saigon\Conpago\DI;

interface IContainerBuilderPersister
{
	function saveContainerBuilder(IPersistableContainerBuilder $builder);
	/**
	 * @return IContainerBuilder
	 */
	function loadContainerBuilder();
}
