<?php

namespace Conpago\DI\Parameters;

use Conpago\DI\NamedParameter as NParameter;
use Conpago\DI\Transformers\DirectTransformer;

class TypedParameter extends Parameter
{
	public static function tryCreate(\ReflectionParameter $parameter, $overridenValue)
	{
		$parameterClass = $parameter->getClass();
		if (!$parameterClass)
			return null;

		$target = ($overridenValue instanceof NParameter) ?
			$overridenValue : '\\' . $parameterClass->getName();
		return new TypedParameter($target, $parameter, DirectTransformer::def());
	}
}
