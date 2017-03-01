<?php

namespace Conpago\DI;

use ClassA;
use ClassN1;
use ClassN10;
use ClassN11;
use ClassN12;
use ClassN13;
use ClassN2;
use ClassN21;
use ClassN22;
use ClassN25;
use ClassN3;
use ClassN31;
use ClassN32;
use ClassN35;
use ClassN4;
use Conpago\DI\Implementation\Factory;

require_once 'DITestCase.php';

class ResolveTypeInNamespace extends DITestCase
{
	public function test_ResolveInNamespace1()
	{
		$this->registerType(\Test\ClassA::class);
		$this->assertInstOf(\Test\ClassA::class, \Test\ClassA::class);
	}

	public function test_ResolveInNamespace2()
	{
		$this->registerType(\Test\ClassA::class);
		$this->assertInstOf(\Test\ClassA::class, \Test\ClassA::class);
	}

	public function test_ResolveParamInNamespaceFromGlobal1()
	{
		// namespace: global
		// param explicitly specified in constructor's parameter list
		$this->registerType(\Test\ClassA::class);
		$this->registerType(ClassN1::class);
		$this->assertInstanceOf(\Test\ClassA::class, $this->resolve(ClassN1::class)->a);
	}

	public function test_ResolveParamInNamespaceFromGlobal2()
	{
		// namespace: global
		// param hint: Test\ClassA
		$this->registerType(\Test\ClassA::class);
		$this->registerType(ClassN2::class);
		$this->assertInstanceOf(\Test\ClassA::class, $this->resolve(ClassN2::class)->a);
	}

	public function test_ResolveParamInNamespaceFromGlobal3()
	{
		// namespace: global
		// param hint: \Test\ClassA
		$this->registerType(\Test\ClassA::class);
		$this->registerType(ClassN3::class);
		$this->assertInstanceOf(\Test\ClassA::class, $this->resolve(ClassN3::class)->a);
	}

	public function test_ResolveParamInNestedNamespaceFromGlobal()
	{
		// namespace: global
		// param explicitly specified in constructor's parameter list
		$this->registerType(\Test\Sub\ClassA::class);
		$this->registerType(ClassN4::class);
		$this->assertInstanceOf(\Test\Sub\ClassA::class, $this->resolve(ClassN4::class)->a);
	}

	public function test_ResolveGlobalParamFromNamespace()
	{
		// namespace: Test
		// param hint: ClassA
		$this->registerType(ClassA::class);
		$this->registerType(\Test\ClassN1::class);
		$this->assertInstanceOf(ClassA::class, $this->resolve(\Test\ClassN1::class)->a);
	}

	public function test_ResolveParamInNamespaceFromSameNamespace1()
	{
		// namespace: Test
		// param hint: \ Test \ ClassA
		$this->registerType(\Test\ClassA::class);
		$this->registerType(\Test\ClassN2::class);
		$this->assertInstanceOf(\Test\ClassA::class, $this->resolve(\Test\ClassN2::class)->a);
	}

	public function test_ResolveParamInNamespaceFromSameNamespace2()
	{
		// namespace: Test
		// param hint: ClassA
		$this->registerType(\Test\ClassA::class);
		$this->registerType(\Test\ClassN3::class);
		$this->assertInstanceOf(\Test\ClassA::class, $this->resolve(\Test\ClassN3::class)->a);
	}

	public function test_ResolveParamInNamespaceFromSameNamespace3()
	{
		// namespace: Test
		// param explicitly specified in constructor's parameter list
		$this->registerType(\Test\ClassA::class);
		$this->registerType(\Test\ClassN7::class);
		$this->assertInstanceOf(\Test\ClassA::class, $this->resolve(\Test\ClassN7::class)->a);
	}

	public function test_ResolveParamInNamespaceFromSameNamespace4()
	{
		// namespace: Test
		// param explicitly specified in constructor's parameter list
		$this->registerType(\Test\ClassA::class);
		$this->registerType(\Test\ClassN8::class);
		$this->assertInstanceOf(\Test\ClassA::class, $this->resolve(\Test\ClassN8::class)->a);
	}

	/**
	 * @expectedException \Conpago\DI\Exceptions\ObjectNotRegisteredException
	 */
	public function test_ResolveParamInNamespaceFromSameNamespace_Fail()
	{
		// namespace: Test
		// param hint: Test\ClassA
		$this->registerType(\Test\ClassA::class);
		$this->registerType(\Test\ClassN4::class);
		$this->assertInstanceOf(\Test\ClassA::class, $this->resolve(\Test\ClassN4::class)->a);
	}

	public function test_ResolveParamInNamespaceFromSubNamespace()
	{
		// namespace: Test\Sub
		// param hint: \Test\ClassA
		$this->registerType(\Test\ClassA::class);
		$this->registerType(\Test\Sub\ClassN1::class);
		$this->assertInstanceOf(\Test\ClassA::class, $this->resolve(\Test\Sub\ClassN1::class)->a);
	}

	public function test_ResolveParamInSubNamespaceFromEnclosingNamespace1()
	{
		// namespace: Test
		// param hint: \Test\Sub\ClassA
		$this->registerType(\Test\Sub\ClassA::class);
		$this->registerType(\Test\ClassN5::class);
		$this->assertInstanceOf(\Test\Sub\ClassA::class, $this->resolve(\Test\ClassN5::class)->a);
	}

	public function test_ResolveParamInSubNamespaceFromEnclosingNamespace2()
	{
		// namespace: Test
		// param hint: Sub\ClassA
		$this->registerType(\Test\Sub\ClassA::class);
		$this->registerType(\Test\ClassN6::class);
		$this->assertInstanceOf(\Test\Sub\ClassA::class, $this->resolve(\Test\ClassN6::class)->a);
	}

	public function test_ResolveCollectionParamInNamespaceFromGlobal1()
	{
		// namespace: global
		// param hint: Test\ClassA
		$this->registerType(\Test\ClassA::class);
		$this->registerType(ClassN10::class);
		$a = $this->resolve(ClassN10::class)->a;
		$this->assertTrue(is_array($a));
		$this->assertInstanceOf(\Test\ClassA::class, $a[0]);
	}

	public function test_ResolveCollectionParamInNamespaceFromGlobal2()
	{
		// namespace: global
		// param hint: \Test\ClassA
		$this->registerType(\Test\ClassA::class);
		$this->registerType(ClassN11::class);
		$a = $this->resolve(ClassN11::class)->a;
		$this->assertTrue(is_array($a));
		$this->assertInstanceOf(\Test\ClassA::class, $a[0]);
	}

	public function test_ResolveCollectionParamInSubNamespaceFromEnclosingNamespace1()
	{
		// namespace: global
		// param hint: Test\Sub\ClassA
		$this->registerType(\Test\Sub\ClassA::class);
		$this->registerType(ClassN12::class);
		$a = $this->resolve(ClassN12::class)->a;
		$this->assertTrue(is_array($a));
		$this->assertInstanceOf(\Test\Sub\ClassA::class, $a[0]);
	}

	public function test_ResolveCollectionParamInSubNamespaceFromEnclosingNamespace2()
	{
		// namespace: global
		// param hint: \Test\Sub\ClassA
		$this->registerType(\Test\Sub\ClassA::class);
		$this->registerType(ClassN13::class);
		$a = $this->resolve(ClassN13::class)->a;
		$this->assertTrue(is_array($a));
		$this->assertInstanceOf(\Test\Sub\ClassA::class, $a[0]);
	}

	public function test_ResolveCollectionParamInSubNamespaceFromGlobal1()
	{
		// namespace: Test
		// param hint: \Test\Sub\ClassA
		$this->registerType(\Test\Sub\ClassA::class);
		$this->registerType(\Test\ClassN10::class);
		$a = $this->resolve(\Test\ClassN10::class)->a;
		$this->assertTrue(is_array($a));
		$this->assertInstanceOf(\Test\Sub\ClassA::class, $a[0]);
	}

	public function test_ResolveCollectionParamInSubNamespaceFromGlobal2()
	{
		// namespace: Test
		// param hint: Sub\ClassA
		$this->registerType(\Test\Sub\ClassA::class);
		$this->registerType(\Test\ClassN11::class);
		$a = $this->resolve(\Test\ClassN11::class)->a;
		$this->assertTrue(is_array($a));
		$this->assertInstanceOf(\Test\Sub\ClassA::class, $a[0]);
	}

	public function test_ResolveFactoryParamInNamespaceFromGlobal1()
	{
		// namespace: global
		// param hint: Factory < \ Test \ ClassA >
		$this->registerType(\Test\ClassA::class);
		$this->registerType(ClassN21::class);
		$this->assertInstanceOf(\Test\ClassA::class, $this->resolve(ClassN21::class)->a->createInstance());
	}

	public function test_ResolveFactoryParamInNamespaceFromGlobal2()
	{
		// namespace: global
		// param hint: Factory<Test\ClassA>
		$this->registerType(\Test\ClassA::class);
		$this->registerType(ClassN22::class);
		$this->assertInstanceOf(\Test\ClassA::class, $this->resolve(ClassN22::class)->a->createInstance());
	}

	public function test_ResolveFactoryGlobalParamFromNamespace()
	{
		// namespace: Test
		// param hint: Factory<\ClassA>
		$this->registerType(ClassA::class);
		$this->registerType(\Test\ClassN21::class);
		$this->assertInstanceOf(ClassA::class, $this->resolve(\Test\ClassN21::class)->a->createInstance());
	}

	public function test_ResolveFactoryParamInSubNamespaceFromNamespace()
	{
		// namespace: Test
		// param hint: Factory<Sub\ClassA>
		$this->registerType(\Test\Sub\ClassA::class);
		$this->registerType(\Test\ClassN22::class);
		$this->assertInstanceOf(\Test\Sub\ClassA::class, $this->resolve(\Test\ClassN22::class)->a->createInstance());
	}

	public function test_ResolveFactoryCollectionParamInNamespaceFromGlobal()
	{
		// namespace: global
		// param hint: Factory<Test\ClassA>
		$this->registerType(\Test\ClassA::class);
		$this->registerType(ClassN25::class);
		/** @var Factory $factory */
        $factory = $this->resolve(ClassN25::class)->a[0];
        $this->assertInstanceOf(\Test\ClassA::class, $factory->createInstance());
	}

	public function test_ResolveFactoryCollectionGlobalParamFromNamespace()
	{
		// namespace: Test
		// param hint: Factory<\ClassA>
		$this->registerType(ClassA::class);
		$this->registerType(\Test\ClassN25::class);
        /** @var Factory $factory */
		$factory = $this->resolve(\Test\ClassN25::class)->a[0];
        $this->assertInstanceOf(ClassA::class, $factory->createInstance());
	}

	public function test_ResolveFactoryCollectionParamInSubNamespaceFromNamespace()
	{
		// namespace: Test
		// param hint: Factory<Sub\ClassA>
		$this->registerType(\Test\Sub\ClassA::class);
		$this->registerType(\Test\ClassN26::class);
		/** @var Factory $factory */
        $factory = $this->resolve(\Test\ClassN26::class)->a[0];
        $this->assertInstanceOf(\Test\Sub\ClassA::class, $factory->createInstance());
	}

	public function test_ResolveLazyParamInNamespaceFromGlobal1()
	{
		// namespace: global
		// param hint: Lazy < \ Test \ ClassA >
		$this->registerType(\Test\ClassA::class);
		$this->registerType(ClassN31::class);
		$this->assertInstanceOf(\Test\ClassA::class, $this->resolve(ClassN31::class)->a->getInstance());
	}

	public function test_ResolveLazyParamInNamespaceFromGlobal2()
	{
		// namespace: global
		// param hint: Lazy<Test\ClassA>
		$this->registerType(\Test\ClassA::class);
		$this->registerType(ClassN32::class);
		$this->assertInstanceOf(\Test\ClassA::class, $this->resolve(ClassN32::class)->a->getInstance());
	}

	public function test_ResolveLazyGlobalParamFromNamespace()
	{
		// namespace: Test
		// param hint: Lazy<\ClassA>
		$this->registerType(ClassA::class);
		$this->registerType(\Test\ClassN31::class);
		$this->assertInstanceOf(ClassA::class, $this->resolve(\Test\ClassN31::class)->a->getInstance());
	}

	public function test_ResolveLazyParamInSubNamespaceFromNamespace()
	{
		// namespace: Test
		// param hint: Lazy<Sub\ClassA>
		$this->registerType(\Test\Sub\ClassA::class);
		$this->registerType(\Test\ClassN32::class);
		$this->assertInstanceOf(\Test\Sub\ClassA::class, $this->resolve(\Test\ClassN32::class)->a->getInstance());
	}

	public function test_ResolveLazyCollectionParamInNamespaceFromGlobal()
	{
		// namespace: global
		// param hint: Lazy<Test\ClassA>
		$this->registerType(\Test\ClassA::class);
		$this->registerType(ClassN35::class);
		/** @var Lazy $lazy */
        $lazy = $this->resolve(ClassN35::class)->a[0];
        $this->assertInstanceOf(\Test\ClassA::class, $lazy->getInstance());
	}

	public function test_ResolveLazyCollectionGlobalParamFromNamespace()
	{
		// namespace: Test
		// param hint: Lazy<\ClassA>
		$this->registerType(ClassA::class);
		$this->registerType(\Test\ClassN35::class);
		/** @var Lazy $lazy */
        $lazy = $this->resolve(\Test\ClassN35::class)->a[0];
        $this->assertInstanceOf(ClassA::class, $lazy->getInstance());
	}

	public function test_ResolveLazyCollectionParamInSubNamespaceFromNamespace()
	{
		// namespace: Test
		// param hint: Lazy<Sub\ClassA>
		$this->registerType(\Test\Sub\ClassA::class);
		$this->registerType(\Test\ClassN36::class);
		/** @var Lazy $lazy */
        $lazy = $this->resolve(\Test\ClassN36::class)->a[0];
        $this->assertInstanceOf(\Test\Sub\ClassA::class, $lazy->getInstance());
	}
}
