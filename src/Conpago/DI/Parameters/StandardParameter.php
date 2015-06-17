<?php

namespace Conpago\DI\Parameters;

use Conpago\DI\NamedParameter;
use Conpago\DI\Transformers\DirectTransformer;

class StandardParameter extends Parameter
{
	public static function tryCreate(\ReflectionParameter $parameter, $overridenValue)
	{
		if (($parameterType = self::getParameterTypeFromDoc($parameter)) === null)
			return null;

		$target = ($overridenValue instanceof NamedParameter) ?
			$overridenValue : $parameterType;
		return new StandardParameter($target, $parameter, DirectTransformer::def());
	}
}
