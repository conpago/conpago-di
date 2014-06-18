<?php

namespace Saigon\Conpago\DI\Parameters;

use Saigon\Conpago\DI\NamedParameter;
use Saigon\Conpago\DI\Transformers\MetaTransformer;

class MetaParameter extends Parameter
{
	public static function tryCreate(\ReflectionParameter $parameter, $overridenValue)
	{
		if (($parameterType = self::getParameterTypeFromDoc($parameter)) === null)
			return null;
		if (($parsed = MetaTransformer::tryParseTarget($parameterType)) === null)
			return null;

		$innerId = ($overridenValue instanceof NamedParameter) ?
			$overridenValue : $parsed->innerId;
		return new MetaParameter($innerId, $parameter, $parsed->transformer);
	}
}
