<?php
	/**
	 * Created by PhpStorm.
	 * User: Bartosz Gołek
	 * Date: 2014-06-12
	 * Time: 22:33
	 */

	namespace DI\Exceptions;


	class ClassNotContainsExpectedConstructor extends \Exception
	{
		public function __construct()
		{
			parent::__construct('Given decorator class does not implement given interface');
		}
	}
