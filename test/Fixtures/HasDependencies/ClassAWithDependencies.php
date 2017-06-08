<?php

namespace ImmoweltHH\Test\DependencyInjection\Fixtures\HasDependencies;

use ImmoweltHH\Test\DependencyInjection\Fixtures\NoDependencies\ClassANoConstructor;

class ClassAWithDependencies
{
    /** @var ClassANoConstructor */
    public $classANoConstructor;

    /**
     * ClassAWithDependencies constructor.
     *
     * @param ClassANoConstructor $classANoConstructor
     */
    public function __construct(ClassANoConstructor $classANoConstructor)
    {
        $this->classANoConstructor = $classANoConstructor;
    }
}
