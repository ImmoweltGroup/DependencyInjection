<?php

namespace ImmoweltHH\Test\DependencyInjection\Tests;

use ImmoweltHH\DependencyInjection\Exception\DependencyInjectionException;
use ImmoweltHH\DependencyInjection\InjectionContainer;
use ImmoweltHH\Test\DependencyInjection\Fixtures\TestInjectionConfig;
use PHPUnit_Framework_TestCase;
use ReflectionProperty;
use ImmoweltHH\Test\DependencyInjection\Fixtures\HasDependencies\ClassAWithDependencies;
use ImmoweltHH\Test\DependencyInjection\Fixtures\HasDependencies\ClassBHasA;
use ImmoweltHH\Test\DependencyInjection\Fixtures\HasDependencies\ClassCHasBAndA;
use ImmoweltHH\Test\DependencyInjection\Fixtures\HasDependencies\ClassWithPreconfiguredDependency;
use ImmoweltHH\Test\DependencyInjection\Fixtures\NoDependencies\ClassAEmptyConstructor;
use ImmoweltHH\Test\DependencyInjection\Fixtures\NoDependencies\ClassANoConstructor;
use ImmoweltHH\Test\DependencyInjection\Fixtures\PreconfiguredClass;

class InjectionContainerInstanciationTest extends PHPUnit_Framework_TestCase
{

    /** @var InjectionContainer */
    private $objectUnderTest;

    /** @var PreconfiguredClass */
    private $preconfiguredClass;

    /**
     * @before
     */
    public function setUp()
    {
        $this->preconfiguredClass = new PreconfiguredClass();
        $this->preconfiguredClass->configuredValue = "abc";

        $this->objectUnderTest = new InjectionContainer(new TestInjectionConfig());
    }

    /**
     * @test
     */
    public function testInstancingWithEmptyConstructor()
    {
        /** @var ClassAEmptyConstructor $result */
        $result = $this->objectUnderTest->get(ClassAEmptyConstructor::class);
        $this->assertThat($result, $this->isInstanceOf(ClassAEmptyConstructor::class));
        $this->assertThat($result->a, $this->equalTo(5));
    }

    /**
     * @test
     */
    public function testInstancingWithNoConstructor()
    {
        /** @var ClassANoConstructor $result */
        $result = $this->objectUnderTest->get(ClassANoConstructor::class);
        $this->assertThat($result, $this->isInstanceOf(ClassANoConstructor::class));
        $this->assertThat($result->a, $this->equalTo(10));
    }

    /**
     * @test
     */
    public function testInstancingWithConstructorArgs()
    {
        /** @var ClassAWithDependencies $result */
        $result = $this->objectUnderTest->get(ClassAWithDependencies::class);
        $this->assertThat($result, $this->isInstanceOf(ClassAWithDependencies::class));
        $this->assertThat($result->classANoConstructor, $this->isInstanceOf(ClassANoConstructor::class));
    }

    /**
     * @test
     */
    public function testInstancingWithComplexObjectGraphNoCR()
    {
        /** @var ClassCHasBAndA $result */
        $result = $this->objectUnderTest->get(ClassCHasBAndA::class);
        $this->assertThat($result, $this->isInstanceOf(ClassCHasBAndA::class));
        $this->assertThat($result->classAWithDependencies, $this->isInstanceOf(ClassAWithDependencies::class));
        $this->assertThat($result->classBHasA, $this->isInstanceOf(ClassBHasA::class));
    }

    /**
     * @test
     */
    public function testInstancingMultipleClassesNoCR() {
        $this->objectUnderTest->get(ClassANoConstructor::class);
        $this->objectUnderTest->get(ClassBHasA::class);
    }

    /**
     * @test
     */
    public function testInstancingWithPreconfiguredDependency()
    {
        /** @var ClassWithPreconfiguredDependency $result */
        $result = $this->objectUnderTest->get(ClassWithPreconfiguredDependency::class);
        $this->assertThat($result, $this->isInstanceOf(ClassWithPreconfiguredDependency::class));
        $this->assertThat($result->preconfiguredClass, $this->isInstanceOf(PreconfiguredClass::class));
        $this->assertThat($result->preconfiguredClass->configuredValue, $this->equalTo("abc"));
    }

    /**
     * @test
     */
    public function testInstancingViaGetAlias()
    {
        $result = $this->objectUnderTest->getAliased("AuctionInfoController");

        $this->assertThat($result, $this->isInstanceOf(ClassANoConstructor::class));
    }

    /**
     * @test
     * @expectedExceptionMessage Alias 'EmployeeController' not found
     */
    public function expectExceptionWhenAliasDoesNotExist()
    {
        $this->setExpectedException(DependencyInjectionException::class);
        $this->objectUnderTest->getAliased("EmployeeController");
    }
}
