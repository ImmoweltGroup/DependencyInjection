<?php

namespace ImmoweltHH\DependencyInjection;

class InjectionContainerBuilder
{
    /** @var InjectionContainer */
    private $container;

    /**
     * InjectionContainerBuilder constructor.
     * @param InjectionContainer $container
     */
    public function __construct(InjectionContainer $container)
    {
        $this->container = $container;
    }

    /**
     * @return InjectionContainerBuilder
     */
    public function debug() {
        $this->container->setDebug(true);
        return $this;
    }

    /**
     * @param string[] $aliases
     * @return InjectionContainerBuilder
     */
    public function registerAliases($aliases)
    {
        foreach ($aliases as $key => $value) {
            $this->container->registerAlias($key, $value);
        }

        return $this;
    }

    /**
     * @param object $object
     *
     * @return InjectionContainerBuilder
     */
    public function registerPreConfiguredObject($object)
    {
        $this->container->registerPreConfiguredClass($object);
        return $this;
    }

    /**
     * @return InjectionContainer
     */
    public function build()
    {
        return $this->container;
    }
}
