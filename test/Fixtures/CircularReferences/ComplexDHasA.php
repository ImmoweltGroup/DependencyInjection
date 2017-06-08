<?php

namespace ImmoweltHH\Test\DependencyInjection\Fixtures\CircularReferences;

class ComplexDHasA
{

    /** @var ComplexAHasBAndC */
    public $complexA;

    /**
     * ComplexDHasA constructor.
     *
     * @param ComplexAHasBAndC $complexA
     */
    public function __construct(ComplexAHasBAndC $complexA)
    {
        $this->complexA = $complexA;
    }
}
