<?php

namespace ImmoweltHH\Test\DependencyInjection\Fixtures\CircularReferences;

class SimpleA
{

    /** @var SimpleB */
    public $b;

    /**
     * SimpleA constructor.
     *
     * @param SimpleB $b
     */
    public function __construct(SimpleB $b)
    {
        $this->b = $b;
    }
}
