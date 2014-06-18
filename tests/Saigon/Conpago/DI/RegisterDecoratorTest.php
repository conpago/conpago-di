<?php
	/**
	 * Created by PhpStorm.
	 * User: Bartosz Gołek
	 * Date: 2014-06-12
	 * Time: 21:34
	 */

	namespace DI;

	require_once 'DITestCase.php';

	class RegisterDecoratorTest extends DITestCase
	{
		public function testRegisterDecoratorMethodReturnsITypeRegisterer()
		{
			$this->assertInstanceOf('\DI\ITypeRegisterer', $this->registerDecorator('InterfaceA1', 'ClassADecorator'));
		}

		/**
		 * @expectedException \DI\Exceptions\ClassNotImplementsInterfaceException
		 */
		public function testRegistererThrowIfClassNotImplementsInterface()
		{
			$this->registerDecorator('InterfaceA2', 'ClassADecorator');
		}

		/**
		 * @expectedException \DI\Exceptions\ClassNotContainsExpectedConstructor
		 */
		public function testRegistererThrowIfCtorDoesNotContainInterfaceArg()
		{
			$this->registerDecorator('InterfaceA1', 'ClassADecorator');
		}
	}