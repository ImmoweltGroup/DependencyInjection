<?php

namespace ImmoweltHH\Test\DependencyInjection\Fixtures\CircularReferences;

class ComplexBHasC
{

    /** @var ComplexCHasDAndE */
    public $complexC;

    /**
     * ComplexBHasC constructor.
     *
     * @param ComplexCHasDAndE $complexC
     */
    public function __construct(ComplexCHasDAndE $complexC)
    {
        $this->complexC = $complexC;
    }
}
