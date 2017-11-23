<?php

namespace ImmoweltHH\Test\DependencyInjection\Fixtures\HasDependencies;

use DateTimeInterface;

class ClassWithConfiguredInterfaceDependencyB
{
    public function __construct(DateTimeInterface $logger)
    {

    }
}
