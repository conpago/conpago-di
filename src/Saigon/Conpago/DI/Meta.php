<?php

namespace Saigon\Conpago\DI;

class Meta extends Lazy
{
	public function getMetadata()
	{
		return $this->getTarget()->getMetadata();
	}
}
