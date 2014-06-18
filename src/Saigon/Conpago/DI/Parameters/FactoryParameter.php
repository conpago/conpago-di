<?php

namespace DI\Parameters;

use DI\NamedParameter;
use DI\Transformers\FactoryTransformer;

class FactoryParameter extends Parameter
{
	public static function tryCreate(\ReflectionParameter $parameter, $overridenValue)
	{
		if (($parameterType = self::getParameterTypeFromDoc($parameter)) === null)
			return null;
		if (($parsed = FactoryTransformer::tryParseTarget($parameterType)) === null)
			return null;

		$innerId = ($overridenValue instanceof NamedParameter) ?
			$overridenValue : $parsed->innerId;
		return new FactoryParameter($innerId, $parameter, $parsed->transformer);
	}
}
