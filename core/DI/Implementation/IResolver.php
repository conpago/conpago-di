<?php

namespace DI\Implementation;

use DI\Transformers\ITransformer;

interface IResolver
{
	function isRegistered($id);
	function resolveWith($id, ITransformer $transformer, $optionalParameters = array());
	function resolveNamedWith($id, ITransformer $transformer, $optionalParameters = array());
	function resolveAllWith($id, ITransformer $transformer);
	function getContainer();
	function getConfiguration();
}
