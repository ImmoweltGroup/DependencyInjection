<?php

namespace ImmoweltHH\Test\DependencyInjection\Tests;

use ImmoweltHH\DependencyInjection\InjectionContainer;
use ImmoweltHH\Test\DependencyInjection\Fixtures\TestInjectionConfig;
use PHPUnit_Framework_TestCase;

class InjectionContainerBasicTest extends PHPUnit_Framework_TestCase
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
    public function expectTrueWhenAliasSet()
    {
        $result = $this->objectUnderTest->hasAlias("AuctionInfoController");

        $this->assertThat($result, $this->isTrue());
    }

    /**
     * @test
     */
    public function expectFalseWhenAliasNotSet()
    {
        $result = $this->objectUnderTest->hasAlias('BlubsiDaisyController');
        $this->assertThat($result, $this->isFalse());
    }
}
