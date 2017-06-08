<?php

namespace ImmoweltHH\Test\DependencyInjection\Fixtures\NoDependencies;

class ClassAEmptyConstructor
{
    public $a;

    public function __construct()
    {
        $this->a = 5;
    }
}
