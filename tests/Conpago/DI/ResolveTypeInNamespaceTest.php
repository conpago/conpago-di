<?php

namespace Conpago\DI;

require_once 'DITestCase.php';

class ResolveTypeInNamespace extends DITestCase
{
	public function test_ResolveInNamespace1()
	{
		$this->registerType('Test\\ClassA');
		$this->assertInstOf('Test\\ClassA', 'Test\\ClassA');
	}

	public function test_ResolveInNamespace2()
	{
		$this->registerType('\\Test\\ClassA');
		$this->assertInstOf('Test\\ClassA', 'Test\\ClassA');
	}

	public function test_ResolveParamInNamespaceFromGlobal1()
	{
		// namespace: global
		// param explicitly specified in constructor's parameter list
		$this->registerType('Test\\ClassA');
		$this->registerType('ClassN1');
		$this->assertInstanceOf('Test\\ClassA', $this->resolve('ClassN1')->a);
	}

	public function test_ResolveParamInNamespaceFromGlobal2()
	{
		// namespace: global
		// param hint: Test\ClassA
		$this->registerType('Test\\ClassA');
		$this->registerType('ClassN2');
		$this->assertInstanceOf('Test\\ClassA', $this->resolve('ClassN2')->a);
	}

	public function test_ResolveParamInNamespaceFromGlobal3()
	{
		// namespace: global
		// param hint: \Test\ClassA
		$this->registerType('Test\\ClassA');
		$this->registerType('ClassN3');
		$this->assertInstanceOf('Test\\ClassA', $this->resolve('ClassN3')->a);
	}

	public function test_ResolveParamInNestedNamespaceFromGlobal()
	{
		// namespace: global
		// param explicitly specified in constructor's parameter list
		$this->registerType('Test\\Sub\\ClassA');
		$this->registerType('ClassN4');
		$this->assertInstanceOf('Test\\Sub\\ClassA', $this->resolve('ClassN4')->a);
	}

	public function test_ResolveGlobalParamFromNamespace()
	{
		// namespace: Test
		// param hint: \ClassA
		$this->registerType('ClassA');
		$this->registerType('Test\\ClassN1');
		$this->assertInstanceOf('ClassA', $this->resolve('Test\\ClassN1')->a);
	}

	public function test_ResolveParamInNamespaceFromSameNamespace1()
	{
		// namespace: Test
		// param hint: \ Test \ ClassA
		$this->registerType('Test\\ClassA');
		$this->registerType('Test\\ClassN2');
		$this->assertInstanceOf('Test\\ClassA', $this->resolve('Test\\ClassN2')->a);
	}

	public function test_ResolveParamInNamespaceFromSameNamespace2()
	{
		// namespace: Test
		// param hint: ClassA
		$this->registerType('Test\\ClassA');
		$this->registerType('Test\\ClassN3');
		$this->assertInstanceOf('Test\\ClassA', $this->resolve('Test\\ClassN3')->a);
	}

	public function test_ResolveParamInNamespaceFromSameNamespace3()
	{
		// namespace: Test
		// param explicitly specified in constructor's parameter list
		$this->registerType('Test\\ClassA');
		$this->registerType('Test\\ClassN7');
		$this->assertInstanceOf('Test\\ClassA', $this->resolve('Test\\ClassN7')->a);
	}

	public function test_ResolveParamInNamespaceFromSameNamespace4()
	{
		// namespace: Test
		// param explicitly specified in constructor's parameter list
		$this->registerType('Test\\ClassA');
		$this->registerType('Test\\ClassN8');
		$this->assertInstanceOf('Test\\ClassA', $this->resolve('Test\\ClassN8')->a);
	}

	/**
	 * @expectedException \Conpago\DI\Exceptions\ObjectNotRegisteredException
	 */
	public function test_ResolveParamInNamespaceFromSameNamespace_Fail()
	{
		// namespace: Test
		// param hint: Test\ClassA
		$this->registerType('Test\\ClassA');
		$this->registerType('Test\\ClassN4');
		$this->assertInstanceOf('Test\\ClassA', $this->resolve('Test\\ClassN4')->a);
	}

	public function test_ResolveParamInNamespaceFromSubNamespace()
	{
		// namespace: Test\Sub
		// param hint: \Test\ClassA
		$this->registerType('Test\\ClassA');
		$this->registerType('Test\\Sub\\ClassN1');
		$this->assertInstanceOf('Test\\ClassA', $this->resolve('Test\\Sub\\ClassN1')->a);
	}

	public function test_ResolveParamInSubNamespaceFromEnclosingNamespace1()
	{
		// namespace: Test
		// param hint: \Test\Sub\ClassA
		$this->registerType('Test\\Sub\\ClassA');
		$this->registerType('Test\\ClassN5');
		$this->assertInstanceOf('Test\\Sub\\ClassA', $this->resolve('Test\\ClassN5')->a);
	}

	public function test_ResolveParamInSubNamespaceFromEnclosingNamespace2()
	{
		// namespace: Test
		// param hint: Sub\ClassA
		$this->registerType('Test\\Sub\\ClassA');
		$this->registerType('Test\\ClassN6');
		$this->assertInstanceOf('Test\\Sub\\ClassA', $this->resolve('Test\\ClassN6')->a);
	}

	public function test_ResolveCollectionParamInNamespaceFromGlobal1()
	{
		// namespace: global
		// param hint: Test\ClassA
		$this->registerType('Test\\ClassA');
		$this->registerType('ClassN10');
		$a = $this->resolve('ClassN10')->a;
		$this->assertTrue(is_array($a));
		$this->assertInstanceOf('Test\\ClassA', $a[0]);
	}

	public function test_ResolveCollectionParamInNamespaceFromGlobal2()
	{
		// namespace: global
		// param hint: \Test\ClassA
		$this->registerType('Test\\ClassA');
		$this->registerType('ClassN11');
		$a = $this->resolve('ClassN11')->a;
		$this->assertTrue(is_array($a));
		$this->assertInstanceOf('Test\\ClassA', $a[0]);
	}

	public function test_ResolveCollectionParamInSubNamespaceFromEnclosingNamespace1()
	{
		// namespace: global
		// param hint: Test\Sub\ClassA
		$this->registerType('Test\\Sub\\ClassA');
		$this->registerType('ClassN12');
		$a = $this->resolve('ClassN12')->a;
		$this->assertTrue(is_array($a));
		$this->assertInstanceOf('Test\\Sub\\ClassA', $a[0]);
	}

	public function test_ResolveCollectionParamInSubNamespaceFromEnclosingNamespace2()
	{
		// namespace: global
		// param hint: \Test\Sub\ClassA
		$this->registerType('Test\\Sub\\ClassA');
		$this->registerType('ClassN13');
		$a = $this->resolve('ClassN13')->a;
		$this->assertTrue(is_array($a));
		$this->assertInstanceOf('Test\\Sub\\ClassA', $a[0]);
	}

	public function test_ResolveCollectionParamInSubNamespaceFromGlobal1()
	{
		// namespace: Test
		// param hint: \Test\Sub\ClassA
		$this->registerType('Test\\Sub\\ClassA');
		$this->registerType('Test\\ClassN10');
		$a = $this->resolve('Test\\ClassN10')->a;
		$this->assertTrue(is_array($a));
		$this->assertInstanceOf('Test\\Sub\\ClassA', $a[0]);
	}

	public function test_ResolveCollectionParamInSubNamespaceFromGlobal2()
	{
		// namespace: Test
		// param hint: Sub\ClassA
		$this->registerType('Test\\Sub\\ClassA');
		$this->registerType('Test\\ClassN11');
		$a = $this->resolve('Test\\ClassN11')->a;
		$this->assertTrue(is_array($a));
		$this->assertInstanceOf('Test\\Sub\\ClassA', $a[0]);
	}

	public function test_ResolveFactoryParamInNamespaceFromGlobal1()
	{
		// namespace: global
		// param hint: Factory < \ Test \ ClassA >
		$this->registerType('Test\\ClassA');
		$this->registerType('ClassN21');
		$this->assertInstanceOf('Test\\ClassA', $this->resolve('ClassN21')->a->createInstance());
	}

	public function test_ResolveFactoryParamInNamespaceFromGlobal2()
	{
		// namespace: global
		// param hint: Factory<Test\ClassA>
		$this->registerType('Test\\ClassA');
		$this->registerType('ClassN22');
		$this->assertInstanceOf('Test\\ClassA', $this->resolve('ClassN22')->a->createInstance());
	}

	public function test_ResolveFactoryGlobalParamFromNamespace()
	{
		// namespace: Test
		// param hint: Factory<\ClassA>
		$this->registerType('ClassA');
		$this->registerType('Test\\ClassN21');
		$this->assertInstanceOf('ClassA', $this->resolve('Test\\ClassN21')->a->createInstance());
	}

	public function test_ResolveFactoryParamInSubNamespaceFromNamespace()
	{
		// namespace: Test
		// param hint: Factory<Sub\ClassA>
		$this->registerType('Test\\Sub\\ClassA');
		$this->registerType('Test\\ClassN22');
		$this->assertInstanceOf('Test\\Sub\\ClassA', $this->resolve('Test\\ClassN22')->a->createInstance());
	}

	public function test_ResolveFactoryCollectionParamInNamespaceFromGlobal()
	{
		// namespace: global
		// param hint: Factory<Test\ClassA>
		$this->registerType('Test\\ClassA');
		$this->registerType('ClassN25');
		$this->assertInstanceOf('Test\\ClassA', $this->resolve('ClassN25')->a[0]->createInstance());
	}

	public function test_ResolveFactoryCollectionGlobalParamFromNamespace()
	{
		// namespace: Test
		// param hint: Factory<\ClassA>
		$this->registerType('ClassA');
		$this->registerType('Test\\ClassN25');
		$this->assertInstanceOf('ClassA', $this->resolve('Test\\ClassN25')->a[0]->createInstance());
	}

	public function test_ResolveFactoryCollectionParamInSubNamespaceFromNamespace()
	{
		// namespace: Test
		// param hint: Factory<Sub\ClassA>
		$this->registerType('Test\\Sub\\ClassA');
		$this->registerType('Test\\ClassN26');
		$this->assertInstanceOf('Test\\Sub\\ClassA', $this->resolve('Test\\ClassN26')->a[0]->createInstance());
	}

	public function test_ResolveLazyParamInNamespaceFromGlobal1()
	{
		// namespace: global
		// param hint: Lazy < \ Test \ ClassA >
		$this->registerType('Test\\ClassA');
		$this->registerType('ClassN31');
		$this->assertInstanceOf('Test\\ClassA', $this->resolve('ClassN31')->a->getInstance());
	}

	public function test_ResolveLazyParamInNamespaceFromGlobal2()
	{
		// namespace: global
		// param hint: Lazy<Test\ClassA>
		$this->registerType('Test\\ClassA');
		$this->registerType('ClassN32');
		$this->assertInstanceOf('Test\\ClassA', $this->resolve('ClassN32')->a->getInstance());
	}

	public function test_ResolveLazyGlobalParamFromNamespace()
	{
		// namespace: Test
		// param hint: Lazy<\ClassA>
		$this->registerType('ClassA');
		$this->registerType('Test\\ClassN31');
		$this->assertInstanceOf('ClassA', $this->resolve('Test\\ClassN31')->a->getInstance());
	}

	public function test_ResolveLazyParamInSubNamespaceFromNamespace()
	{
		// namespace: Test
		// param hint: Lazy<Sub\ClassA>
		$this->registerType('Test\\Sub\\ClassA');
		$this->registerType('Test\\ClassN32');
		$this->assertInstanceOf('Test\\Sub\\ClassA', $this->resolve('Test\\ClassN32')->a->getInstance());
	}

	public function test_ResolveLazyCollectionParamInNamespaceFromGlobal()
	{
		// namespace: global
		// param hint: Lazy<Test\ClassA>
		$this->registerType('Test\\ClassA');
		$this->registerType('ClassN35');
		$this->assertInstanceOf('Test\\ClassA', $this->resolve('ClassN35')->a[0]->getInstance());
	}

	public function test_ResolveLazyCollectionGlobalParamFromNamespace()
	{
		// namespace: Test
		// param hint: Lazy<\ClassA>
		$this->registerType('ClassA');
		$this->registerType('Test\\ClassN35');
		$this->assertInstanceOf('ClassA', $this->resolve('Test\\ClassN35')->a[0]->getInstance());
	}

	public function test_ResolveLazyCollectionParamInSubNamespaceFromNamespace()
	{
		// namespace: Test
		// param hint: Lazy<Sub\ClassA>
		$this->registerType('Test\\Sub\\ClassA');
		$this->registerType('Test\\ClassN36');
		$this->assertInstanceOf('Test\\Sub\\ClassA', $this->resolve('Test\\ClassN36')->a[0]->getInstance());
	}
}
