<?php

namespace ImmoweltHH\Test\DependencyInjection\Fixtures\HasDependencies;


class ClassCHasBAndA
{
    /** @var ClassBHasA */
    public $classBHasA;

    /** @var ClassAWithDependencies */
    public $classAWithDependencies;

    /**
     * ClassCHasBAndA constructor.
     *
     * @param ClassBHasA $classBHasA
     * @param ClassAWithDependencies $classAWithDependencies
     */
    public function __construct(ClassBHasA $classBHasA, ClassAWithDependencies $classAWithDependencies)
    {
        $this->classBHasA = $classBHasA;
        $this->classAWithDependencies = $classAWithDependencies;
    }
}
