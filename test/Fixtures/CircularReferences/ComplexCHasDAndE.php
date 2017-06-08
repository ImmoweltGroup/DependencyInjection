<?php

namespace ImmoweltHH\Test\DependencyInjection\Fixtures\CircularReferences;

class ComplexCHasDAndE
{
    /** @var ComplexDHasA */
    public $complexD;

    /** @var ComplexE */
    public $complexE;

    /**
     * ComplexCHasDAndE constructor.
     *
     * @param ComplexDHasA $complexD
     * @param ComplexE $complexE
     */
    public function __construct(ComplexDHasA $complexD, ComplexE $complexE)
    {
        $this->complexD = $complexD;
        $this->complexE = $complexE;
    }
}
