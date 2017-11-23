<?php

namespace ImmoweltHH\Test\DependencyInjection\Fixtures;

use DateTimeInterface;
use ImmoweltHH\DependencyInjection\InjectionConfig;
use ImmoweltHH\Test\DependencyInjection\Fixtures\NoDependencies\ClassANoConstructor;

class TestInjectionConfig implements InjectionConfig
{

    /**
     * Returns a map of alias => class name
     *
     * @return string[]
     */
    public function aliases()
    {
        return [
            "AuctionInfoController" => ClassANoConstructor::class
        ];
    }

    /**
     * Returns a map of interface => class name
     *
     * @return string[]
     */
    public function interfaces()
    {
        return [
            LoggerInterface::class => LoggerImpl::class,
            DateTimeInterface::class => LoggerImpl::class
        ];
    }

    /**
     * Returns a list of objects that should be used for instanciation
     * instead of a clean object
     *
     * @return object[]
     */
    public function preconfiguredClasses()
    {
        return [
            $this->preconfigureObject()
        ];
    }

    /**
     * Return true to enable debug print
     *
     * @return boolean
     */
    public function debug()
    {
        return false;
    }

    private function preconfigureObject()
    {
        $preconfiguredClass = new PreconfiguredClass();
        $preconfiguredClass->configuredValue = "abc";

        return $preconfiguredClass;
    }
}
