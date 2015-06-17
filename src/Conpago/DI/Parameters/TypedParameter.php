<?php

namespace Saigon\Conpago\DI\Parameters;

use Saigon\Conpago\DI\NamedParameter;
use Saigon\Conpago\DI\Transformers\DirectTransformer;

class TypedParameter extends Parameter
{
	public static function tryCreate(\ReflectionParameter $parameter, $overridenValue)
	{
		$parameterClass = $parameter->getClass();
		if (!$parameterClass)
			return null;

		$target = ($overridenValue instanceof NamedParameter) ?
			$overridenValue : '\\' . $parameterClass->getName();
		return new TypedParameter($target, $parameter, DirectTransformer::def());
	}
}
