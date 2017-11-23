<?php

namespace ImmoweltHH\Test\DependencyInjection\Fixtures\HasDependencies;

use ImmoweltHH\Test\DependencyInjection\Fixtures\LoggerInterface;

class ClassWithConfiguredInterfaceDependencyA
{
    public function __construct(LoggerInterface $logger)
    {

    }
}
