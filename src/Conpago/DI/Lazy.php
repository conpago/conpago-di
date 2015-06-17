<?php

namespace Conpago\DI;

use Conpago\DI\Implementation\Intermediate;

class Lazy extends Intermediate
{
	public function getInstance()
	{
		if (!$this->isResolved)
			$this->resolve();
		return $this->instance;
	}

	private function resolve()
	{
		$this->instance = $this->getTarget()->resolve($this->getContainer(),
			Transformers\DirectTransformer::def(), array());
		$this->isResolved = true;
	}

	private $isResolved;
	private $instance;
}
