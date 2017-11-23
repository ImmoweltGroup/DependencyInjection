<?php

namespace ImmoweltHH\DependencyInjection;

interface InjectionConfig
{
    /**
     * Returns a map of alias => class name
     *
     * @return string[]
     */
    public function aliases();

    /**
     * Returns a map of interface => class name
     *
     * @return string[]
     */
    public function interfaces();

    /**
     * Returns a list of objects that should be used for instanciation
     * instead of a clean object
     *
     * @param InjectionContainer $container
     *
     * @return object[]
     */
    public function preconfiguredClasses(InjectionContainer $container);

    /**
     * Return true to enable debug print
     *
     * @return boolean
     */
    public function debug();
}
