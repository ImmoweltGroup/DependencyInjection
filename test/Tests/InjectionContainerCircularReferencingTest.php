<?php

namespace ImmoweltHH\Test\DependencyInjection\Tests;


use ImmoweltHH\DependencyInjection\Exception\CircularReferenceException;
use ImmoweltHH\DependencyInjection\InjectionContainer;
use ImmoweltHH\Test\DependencyInjection\Fixtures\TestInjectionConfig;
use PHPUnit_Framework_TestCase;
use ImmoweltHH\Test\DependencyInjection\Fixtures\CircularReferences\ComplexAHasBAndC;
use ImmoweltHH\Test\DependencyInjection\Fixtures\CircularReferences\SimpleA;

class InjectionContainerCircularReferencingTest extends PHPUnit_Framework_TestCase
{

    /** @var InjectionContainer */
    private $objectUnderTest;

    /**
     * @before
     */
    public function setUp()
    {
        $this->objectUnderTest = new InjectionContainer(new TestInjectionConfig());
    }

    /**
     * @test
     */
    public function expectExceptionOnBasicCircularDependency()
    {
        $this->setExpectedException(CircularReferenceException::class);
        $this->objectUnderTest->get(SimpleA::class);
    }

    /**
     * @test
     */
    public function expectExceptionOnComplexCircularDependency()
    {
        $this->setExpectedException(CircularReferenceException::class);
        $this->objectUnderTest->get(ComplexAHasBAndC::class);
    }
}
