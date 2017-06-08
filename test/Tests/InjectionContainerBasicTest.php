<?php

namespace ImmoweltHH\Test\DependencyInjection\Tests;

use ImmoweltHH\DependencyInjection\Exception\DependencyInjectionException;
use ImmoweltHH\DependencyInjection\InjectionContainer;
use ImmoweltHH\Test\DependencyInjection\Fixtures\NoDependencies\ClassANoConstructor;
use ImmoweltHH\Test\DependencyInjection\Fixtures\PreconfiguredClass;
use PHPUnit_Framework_TestCase;
use ReflectionProperty;

class InjectionContainerBasicTest extends PHPUnit_Framework_TestCase
{

    /** @var InjectionContainer */
    private $objectUnderTest;

    /**
     * @before
     */
    public function setUp()
    {
        $this->objectUnderTest = new InjectionContainer();
    }

    /**
     * @test
     */
    public function expectSubjectToContainPreconfiguredClass()
    {
        $preconfiguredClass = new PreconfiguredClass();
        $preconfiguredClass->configuredValue = "abc";

        $this->objectUnderTest->registerPreConfiguredClass($preconfiguredClass);

        $property = new ReflectionProperty(InjectionContainer::class, "cache");
        $property->setAccessible(true);
        $result = $property->getValue($this->objectUnderTest);

        $this->assertThat($result, $this->contains($preconfiguredClass));
    }

    /**
     * @test
     */
    public function expectTrueWhenAliasSet()
    {
        $property = new ReflectionProperty(InjectionContainer::class, "aliases");
        $property->setAccessible(true);
        $property->setValue($this->objectUnderTest, ["AuctionInfoController" => ClassANoConstructor::class]);

        $result = $this->objectUnderTest->hasAlias("AuctionInfoController");

        $this->assertThat($result, $this->isTrue());
    }

    /**
     * @test
     */
    public function expectFalseWhenAliasNotSet()
    {
        $property = new ReflectionProperty(InjectionContainer::class, "aliases");
        $property->setAccessible(true);
        $property->setValue($this->objectUnderTest, ["AuctionInfoControllerBlub" => ClassANoConstructor::class]);

        $result = $this->objectUnderTest->hasAlias('AuctionInfoController');
        $this->assertThat($result, $this->isFalse());
    }

    /**
     * @test
     */
    public function testRegisterAlias() {
        $this->objectUnderTest->registerAlias("AuctionInfoController", ClassANoConstructor::class);

        $property = new ReflectionProperty(InjectionContainer::class, "aliases");
        $property->setAccessible(true);
        $result = $property->getValue($this->objectUnderTest);

        $this->assertThat($result, $this->equalTo(["AuctionInfoController" => ClassANoConstructor::class]));
    }

    /**
     * @test
     * @expectedExceptionMessage Class 'BlubsiDaisy\ClassThatDoesNotExist' does not exist
     */
    public function expectExceptionWhenClassDoesNotExist() {
        $this->setExpectedException(DependencyInjectionException::class);
        $this->objectUnderTest->registerAlias("AuctionInfoController", 'BlubsiDaisy\ClassThatDoesNotExist');
    }
}
