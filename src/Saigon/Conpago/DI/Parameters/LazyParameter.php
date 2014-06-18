<?php

namespace DI\Parameters;

use DI\NamedParameter;
use DI\Transformers\LazyTransformer;

class LazyParameter extends Parameter
{
	public static function tryCreate(\ReflectionParameter $parameter, $overridenValue)
	{
		if (($parameterType = self::getParameterTypeFromDoc($parameter)) === null)
			return null;
		if (($parsed = LazyTransformer::tryParseTarget($parameterType)) === null)
			return null;

		$innerId = ($overridenValue instanceof NamedParameter) ?
			$overridenValue : $parsed->innerId;
		return new LazyParameter($innerId, $parameter, $parsed->transformer);
	}
}
