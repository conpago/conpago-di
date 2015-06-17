<?php

require_once 'Classes2.php';

class ClassDD
{
	/**
	 * @inject InterfaceA1 $intf
	 */
	public function __construct(array $intf)
	{ $this->intf = $intf; }
}

class ClassG extends ClassE
{
	public function __construct(ClassA $classA, $value, ClassB $classB, $value2)
	{ parent::__construct($value); $this->classA = $classA; $this->classB = $classB; $this->value2 = $value2; }
}

class ClassH extends ClassD
{}

class ClassK
{
	public function __construct(ClassA $classA)
	{ $this->classA = $classA; }
}

class ClassL extends ClassK
{
	public function __construct(ClassA $classA, ClassD $classD)
	{ parent::__construct($classA); $this->classD = $classD; }
}

class ClassM extends ClassK
{
	/**
	 * @inject ClassD $classD
	 */
	public function __construct(ClassA $classA, $classD)
	{ parent::__construct($classA); $this->classD = $classD; }
}

class ClassN
{
	public function __construct($value, ClassA $classA)
	{ $this->value = $value; $this->classA = $classA; }
}

class ClassO
{
	public function __construct(ClassA $classA, $classD)
	{}
}

class ClassP
{
	/**
	 * @inject Factory<ClassA> $classAFactory
	 */
	public function __construct($classAFactory)
	{ $this->classAFactory = $classAFactory; }
	public function getClassA()
	{ return $this->classAFactory->createInstance(); }
}

class ClassQ
{
	/**
	 * @inject Factory<ClassG> $classGFactory
	 */
	public function __construct($classGFactory)
	{ $this->classGFactory = $classGFactory; }
	public function getClassG($value, $value2)
	{ return $this->classGFactory->createInstance(Conpago\DI\Parameter::def(), $value, Conpago\DI\Parameter::def(), $value2); }
}

class ClassR
{
	/**
	 * @inject Lazy<ClassD> $lazyClassD
	 */
	public function __construct($lazyClassD)
	{ $this->lazyClassD = $lazyClassD; }
	public function getClassD()
	{ return $this->lazyClassD->getInstance(); }
}

class ClassX
{
	/**
	 * @inject Lazy<ClassY> $lazyClassY
	 */
	public function __construct($lazyClassY)
	{ $this->lazyClassY = $lazyClassY; }
	public function getClassY()
	{ return $this->lazyClassY->getInstance(); }
}

class ClassY
{
	public function __construct(ClassX $classX)
	{ $this->classX = $classX; }
	public function getClassX()
	{ return $this->classX; }
}

class ClassZ
{
	/**
	 * @inject ClassE $e2
	 */
	public function __construct(ClassE $e1, $value, $e2)
	{ $this->e1 = $e1; $this->value = $value; $this->e2 = $e2; }
}

class ClassZZ
{
	/**
	 * @inject Factory<ClassZ> $zFactory
	 */
	public function __construct($zFactory)
	{ $this->zFactory = $zFactory; }
	public function getZ1(ClassE $e)
	{ return $this->zFactory->createInstance(Conpago\DI\Parameter::def(), '', $e); }
	public function getZ2(ClassE $e)
	{ return $this->zFactory->createInstance($e, '', Conpago\DI\Parameter::def()); }
}

class ClassAA
{
	/**
	 * @inject Factory<ClassA> $aFactories
	 */
	public function __construct(array $aFactories)
	{ $this->aFactories = $aFactories; }
	public function getA()
	{ return $this->aFactories['a']->createInstance(); }
	public function getB()
	{ return $this->aFactories['b']->createInstance(); }
}

class ClassAB
{
	/**
	 * @inject Lazy<ClassA> $aObjects
	 */
	public function __construct(array $aObjects)
	{ $this->aObjects = $aObjects; }
	public function getA()
	{ return $this->aObjects['a']->getInstance(); }
	public function getB()
	{ return $this->aObjects['b']->getInstance(); }
}

class ClassBA
{
	/**
	 * @inject Lazy < ClassA > $aObject
	 */
	public function __construct($aObject)
	{ $this->aObject = $aObject; }
	public function getA()
	{ return $this->aObject->getInstance(); }
}

class ClassBB
{
	/**
	 * @inject Factory < ClassA > $aObject
	 */
	public function __construct($aObject)
	{ $this->aObject = $aObject; }
	public function getA()
	{ return $this->aObject->createInstance(); }
}

class ClassBC
{
	/**
	 * @inject Lazy < ClassA > $aObjects
	 */
	public function __construct(array $aObjects)
	{ $this->aFactories = $aObjects; }
	public function getA()
	{ return $this->aFactories['a']->getInstance(); }
	public function getB()
	{ return $this->aFactories['b']->getInstance(); }
}

class ClassBD
{
	/**
	 * @inject Factory < ClassA > $aObjects
	 */
	public function __construct(array $aObjects)
	{ $this->aFactories = $aObjects; }
	public function getA()
	{ return $this->aFactories['a']->createInstance(); }
	public function getB()
	{ return $this->aFactories['b']->createInstance(); }
}

