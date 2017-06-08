<?php

namespace ImmoweltHH\Test\DependencyInjection\Fixtures\CircularReferences;

class ComplexE
{

    /** @var SimpleA */
    public $simpleA;

    /**
     * ComplexE constructor.
     *
     * @param SimpleA $simpleA
     */
    public function __construct(SimpleA $simpleA)
    {
        $this->simpleA = $simpleA;
    }
}
