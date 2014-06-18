<?php

namespace DI\Parameters;

use DI\Implementation\IResolver;
use DI\NamedParameter;
use DI\Transformers\ITransformer;

abstract class Parameter implements IParameter
{
	protected function __construct($target, \ReflectionParameter $parameter, ITransformer $transformer)
	{
		$this->targetName = ($target instanceof NamedParameter) ?
			'Name: ' . $target->getName() :	self::resolveNamespace($target, $parameter);
		$this->isArray = $parameter->isArray();
		$this->transformer = $transformer;
	}

	public function resolve(IResolver $container)
	{
		$resolveMethod = $this->getResolveMethodName();
		return $container->$resolveMethod($this->targetName, $this->transformer);
	}

	protected static function getParameterTypeFromDoc(\ReflectionParameter $parameter)
	{
		$constructor = $parameter->getDeclaringFunction();
		$docComment = $constructor->getDocComment();

		if (!preg_match('#@inject(?<target>.+)\$' . $parameter->getName() . '#', $docComment, $match))
			return null;

		return str_replace(' ', '', $match['target']);
	}

	protected function getResolveMethodName()
	{
		return $this->isArray() ? 'resolveAllWith' : 'resolveWith';
	}

	public function getTargetName()
	{
		return $this->targetName;
	}

	public function isArray()
	{
		return $this->isArray;
	}

	protected static function resolveNamespace($typeName, \ReflectionParameter $parameter)
	{
		return ($typeName[0] == '\\' ? '' : self::getNamespaceOfParameter($parameter)) . ltrim($typeName, '\\');
	}

	private static function getNamespaceOfParameter(\ReflectionParameter $parameter)
	{
		$class = $parameter->getDeclaringClass();
		return $class->inNamespace() ? $class->getNamespaceName() . '\\' : '';
	}

	private $targetName;
	private $isArray;
	private $transformer;
}
