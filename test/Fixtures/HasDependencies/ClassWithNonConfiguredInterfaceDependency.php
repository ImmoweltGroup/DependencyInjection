<?php

namespace ImmoweltHH\Test\DependencyInjection\Fixtures\HasDependencies;

use JsonSerializable;

class ClassWithNonConfiguredInterfaceDependency
{
    public function __construct(JsonSerializable $logger)
    {
    }
}
