<?php

namespace ImmoweltHH\Test\DependencyInjection\Fixtures\HasDependencies;

use ImmoweltHH\Test\DependencyInjection\Fixtures\NoDependencies\ClassANoConstructor;

class ClassBHasA
{
    /** @var ClassAWithDependencies */
    public $classAWithDependencies;

    /** @var ClassANoConstructor */
    private $classANoConstructor;

    /**
     * ClassBHasA constructor.
     *
     * @param ClassAWithDependencies $classAWithDependencies
     * @param ClassANoConstructor $classANoConstructor
     */
    public function __construct(ClassANoConstructor $classANoConstructor, ClassAWithDependencies $classAWithDependencies)
    {
        $this->classAWithDependencies = $classAWithDependencies;
        $this->classANoConstructor = $classANoConstructor;
    }
}
