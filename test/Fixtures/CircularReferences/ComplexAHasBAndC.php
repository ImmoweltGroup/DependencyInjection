<?php

namespace ImmoweltHH\Test\DependencyInjection\Fixtures\CircularReferences;

class ComplexAHasBAndC
{
    /** @var ComplexBHasC */
    public $complexB;

    /** @var ComplexCHasDAndE */
    public $complexC;

    /**
     * ComplexAHasBAndC constructor.
     *
     * @param ComplexBHasC $complexB
     * @param ComplexCHasDAndE $complexC
     */
    public function __construct(ComplexBHasC $complexB, ComplexCHasDAndE $complexC)
    {
        $this->complexB = $complexB;
        $this->complexC = $complexC;
    }
}
