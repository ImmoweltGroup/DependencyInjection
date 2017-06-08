<?php

namespace ImmoweltHH\Test\DependencyInjection\Fixtures\HasDependencies;

use ImmoweltHH\Test\DependencyInjection\Fixtures\PreconfiguredClass;

class ClassWithPreconfiguredDependency
{
    /** @var PreconfiguredClass */
    public $preconfiguredClass;

    /**
     * ClassWithPreconfiguredDependency constructor.
     *
     * @param PreconfiguredClass $preconfiguredClass
     */
    public function __construct(PreconfiguredClass $preconfiguredClass)
    {
        $this->preconfiguredClass = $preconfiguredClass;
    }
}
