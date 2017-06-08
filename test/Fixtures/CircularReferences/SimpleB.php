<?php

namespace ImmoweltHH\Test\DependencyInjection\Fixtures\CircularReferences;

class SimpleB
{

    /** @var SimpleA */
    public $a;

    /**
     * SimpleB constructor.
     *
     * @param SimpleA $a
     */
    public function __construct(SimpleA $a)
    {
        $this->a = $a;
    }
}
