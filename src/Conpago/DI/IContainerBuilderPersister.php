<?php

namespace Conpago\DI;

interface IContainerBuilderPersister
{
	function saveContainerBuilder(IPersistableContainerBuilder $builder);
	/**
	 * @return IContainerBuilder
	 */
	function loadContainerBuilder();
}
