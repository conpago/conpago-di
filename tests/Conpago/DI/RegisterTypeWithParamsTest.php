<?php

namespace Conpago\DI;

use ClassA;
use ClassE3;
use ClassF1;
use ClassF2;
use ClassM1;
use ClassN22;
use ClassN32;

require_once 'DITestCase.php';

class RegisterTypeWithParamsTest extends DITestCase
{
    public function test_RegisterWithParams_WithDefaultAtEnd()
    {
        $this->registerType(ClassE3::class)->withParams('testE3');
        $this->assertEquals('testE3', $this->resolve(ClassE3::class)->value);
        $this->assertEquals('e3', $this->resolve(ClassE3::class)->value2);
    }

    public function test_RegisterWithParams_WithDefaultFirst()
    {
        $this->registerType(ClassE3::class)->withParams(Parameter::def(), 'testE3');
        $this->assertEquals('e', $this->resolve(ClassE3::class)->value);
        $this->assertEquals('testE3', $this->resolve(ClassE3::class)->value2);
    }

    public function test_RegisterDelayedLoadedParams()
    {
        $this->registerType(ClassF1::class);
        $this->assertTrue(TRUE);
    }

    /**
     * @expectedException \ReflectionException
     */
    public function test_RegisterDelayedLoadedParams_Resolve_Fail()
    {
        $this->registerType('Delayed\\ClassB');
        $this->registerType(ClassF1::class);
        $this->resolve(ClassF1::class);
    }

    public function test_RegisterLazyDelayedLoadedParams_Resolve()
    {
        $this->registerType('Delayed\\ClassB');
        $this->registerType(ClassF2::class);
        $this->resolve(ClassF2::class);

        $this->assertTrue(TRUE);
    }

    public function test_RegisterWithLazyParam_ResolveNamed()
    {
        $counter = 0;
        $this->registerType(\Test\ClassA::class)->named('test')
            ->onActivated(function () use (&$counter) {
                $counter++;
            });
        $this->registerType(ClassN32::class)->withParams(Parameter::named('test'));
        $c = $this->resolve(ClassN32::class);
        $this->assertEquals(0, $counter);
        $c->a->getInstance();
        $this->assertEquals(1, $counter);
    }

    public function test_RegisterWithMetaParam_ResolveNamed()
    {
        $counter = 0;
        $this->registerType(ClassA::class)->named('test')
            ->withMetadata('a')
            ->onActivated(function () use (&$counter) {
                $counter++;
            });
        $this->registerType(ClassM1::class)->withParams(Parameter::named('test'));
        $c = $this->resolve(ClassM1::class);
        $this->assertEquals(0, $counter);
        $this->assertEquals('a', $c->a->getMetadata());
        $c->a->getInstance();
        $this->assertEquals(1, $counter);
    }

    public function test_RegisterWithFactoryParam_ResolveNamed()
    {
        $counter = 0;
        $this->registerType(\Test\ClassA::class)->named('test')
            ->onActivated(function () use (&$counter) {
                $counter++;
            });
        $this->registerType(ClassN22::class)->withParams(Parameter::named('test'));
        $c = $this->resolve(ClassN22::class);
        $this->assertEquals(0, $counter);
        $c->a->createInstance();
        $this->assertEquals(1, $counter);
    }
}
