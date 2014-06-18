<?php

namespace Saigon\Conpago\DI\Implementation;

use Saigon\Conpago\DI\Exceptions\ObjectNotRegisteredException;
use Saigon\Conpago\DI\Exceptions\UnrelatedClassesException;
use Saigon\Conpago\DI\Resolvables\CompositeResolvable;
use Saigon\Conpago\DI\Transformers\DirectTransformer;
use Saigon\Conpago\DI\Transformers\ITransformer;

class TypeRepository implements IResolver
{
	public function __construct(array $registry)
	{
		$this->registry = $registry;
	}

	public function getContainer()
	{
		if (!$this->container)
			$this->container = new Container($this);
		return $this->container;
	}

	public function getConfiguration()
	{
		return $this->registry;
	}

	public function resolveWith($id, ITransformer $transformer, $optionalParameters = array())
	{
		$refined = $this->refineTransformerIfPossible($id, $transformer);
		$result = $this->findTarget($refined->innerId)->transform($this, $refined->transformer, $optionalParameters);
		$this->checkTypeComplianceOfResult($result, $refined->innerId);
		return $result;
	}

	public function resolveNamedWith($id, ITransformer $transformer, $optionalParameters = array())
	{
		return $this->resolveWith('Name: ' . $id, $transformer, $optionalParameters);
	}

	public function resolveAllWith($id, ITransformer $transformer)
	{
		$refined = $this->refineTransformerIfPossible($id, $transformer);
		$this->prepareContainerIfNeeded($refined->innerId);
		$result = $this->resolveWith('Container: ' . $refined->innerId, $refined->transformer);
		$this->checkTypeComplianceOfResult($result, $refined->innerId);
		return $result;
	}

	private function prepareContainerIfNeeded($id)
	{
		if (!$this->hasTarget('Container: ' . $id))
			$this->makeComposite($id);
	}

	public function isRegistered($id)
	{
		return isset($this->registry[$id]);
	}

	private function refineTransformerIfPossible($id, ITransformer $transformer)
	{
		if ($transformer instanceof DirectTransformer)
			return $this->refineDirectTransformer($id);
		return (object)array('innerId' => $id, 'transformer' => $transformer);
	}

	private function refineDirectTransformer($id)
	{
		foreach ($this->getTransformerTypes() as $transformerType)
		{
			$transformerClass = 'Saigon\\Conpago\\DI\\Transformers\\' . $transformerType . 'Transformer';
			if (($parsed = $transformerClass::tryParseTarget($id)) !== null)
				return $parsed;
		}
		return (object)array('innerId' => $id, 'transformer' => DirectTransformer::def());
	}

	private function getTransformerTypes()
	{
		return array('Meta', 'Lazy', 'Factory');
	}

	private function hasTarget($id)
	{
		return isset($this->registry[$id]);
	}

	private function findTarget($id)
	{
		if (!$this->hasTarget($id))
			throw new ObjectNotRegisteredException($id);

		return $this->registry[$id];
	}

	private function makeComposite($id)
	{
		$composite = new CompositeResolvable($this->isRegistered($id) ? $this->registry[$id] : null);
		return $this->registry['Container: ' . $id] = $composite;
	}

	private function checkTypeComplianceOfResult($result, $type)
	{
		if (!$this->isTypeName($type))
			return;
		foreach ((is_array($result) ? $result : array($result)) as $object)
			$this->checkTypeComplianceOfObject($object, $type);
	}

	private function isTypeName($id)
	{
		return preg_match('#^[a-z_]\w+$#i', $id);
	}

	private function checkTypeComplianceOfObject($object, $type)
	{
		if ($object && !($object instanceof IIntermediate) && !($object instanceof $type))
			throw new UnrelatedClassesException(get_class($object), $type);
	}

	private $registry;
	private $container;
}
