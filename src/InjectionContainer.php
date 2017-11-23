<?php

namespace ImmoweltHH\DependencyInjection;

use ImmoweltHH\DependencyInjection\Exception\CircularReferenceException;
use ImmoweltHH\DependencyInjection\Exception\DependencyInjectionException;
use ReflectionClass;
use ReflectionParameter;

class InjectionContainer
{

    /** @var object[] */
    private $cache = [];

    /** @var string[] */
    private $circularReferenceCache;

    /** @var DependencyTree */
    private $dependencyTree;

    /** @var InjectionConfig */
    private $config;

    /**
     * InjectionContainer constructor.
     *
     * @param InjectionConfig $injectionConfig
     */
    public function __construct(InjectionConfig $injectionConfig)
    {
        $this->config = $injectionConfig;
        $this->importPreConfiguredClasses();
        $this->circularReferenceCache = [];
        $this->dependencyTree = new DependencyTree();
    }

    private function importPreConfiguredClasses()
    {
        foreach ($this->config->preconfiguredClasses($this) as $preconfiguredClass) {
            $name = (new ReflectionClass($preconfiguredClass))->getName();
            $this->cache[$name] = $preconfiguredClass;
        }
    }

    /**
     * @param string $alias
     *
     * @return bool
     */
    public function hasAlias($alias)
    {
        return isset($this->config->aliases()[$alias]);
    }

    /**
     * @param string $alias
     *
     * @return object
     * @throws DependencyInjectionException
     */
    public function getAliased($alias)
    {
        if ($this->hasAlias($alias)) {
            return $this->get($this->config->aliases()[$alias]);
        }

        throw new DependencyInjectionException(sprintf("Alias '%s' not found", $alias));
    }

    /**
     * @param string $className
     *
     * @return object
     */
    public function get($className)
    {
        $this->dependencyTree->reset();
        return $this->instanciateClass(new ReflectionClass($className));
    }

    /**
     * @param ReflectionClass $class
     *
     * @return object
     */
    private function instanciateClass(ReflectionClass $class)
    {
        if (isset($this->cache[$class->getName()])) {
            return $this->cache[$class->getName()];
        }

        $this->checkForCircularReference($class);

        $this->printCurrentPath();

        $constructor = $class->getConstructor();
        if ($constructor === null || $constructor->getNumberOfParameters() == 0) {
            $instance = $class->newInstance();
        } else {
            $constructorArgsClasses = array_map([$this, 'getClass'], $constructor->getParameters());
            $instance = $class->newInstanceArgs(array_map([$this, 'instanciateClass'], $constructorArgsClasses));
        }

        $this->cache[$class->getName()] = $instance;
        $this->dependencyTree->killBottomNode();

        return $instance;
    }

    /**
     * @param ReflectionParameter $param
     * TODO: add test for null-check
     *
     * @return ReflectionClass
     * @throws DependencyInjectionException
     */
    private function getClass(ReflectionParameter $param)
    {
        $this->assertParamTypeIsSet($param);

        if ($param->getClass()->isInterface()) {
            $this->assertInterfaceIsConfigured($param);
            $reflectionClass = new ReflectionClass($this->config->interfaces()[$param->getClass()->getName()]);
            $this->assertConfiguredClassImplementsInterface($param, $reflectionClass);

            return $reflectionClass;
        }

        return $param->getClass();
    }

    /**
     * @param ReflectionClass $class
     */
    private function checkForCircularReference(ReflectionClass $class) {
        $alreadyInTree = $this->dependencyTree->treeContains($class->getName());
        $this->dependencyTree->addNode($class->getName());
        if ($alreadyInTree) {
            $this->handleCircularReference();
        }
    }

    /**
     * @throws CircularReferenceException
     */
    private function handleCircularReference() {
        $message = sprintf(
            "The following classes are having circular referencing: %s",
            $this->dependencyTree->toString()
        );
        throw new CircularReferenceException($message);
    }

    private function printCurrentPath()
    {
        if ($this->config->debug()) {
            echo $this->dependencyTree->toString() . PHP_EOL;
        }
    }

    /**
     * @param ReflectionParameter $param
     *
     * @throws DependencyInjectionException
     */
    private function assertParamTypeIsSet(ReflectionParameter $param)
    {
        if ($param->getClass() === null) {
            throw new DependencyInjectionException(
                sprintf(
                    "Parameter %d ('%s') in %s cannot be instanciated (no type given)",
                    $param->getPosition(),
                    $param->getName(),
                    $param->getDeclaringClass()->getName()
                )
            );
        }
    }

    /**
     * @param ReflectionParameter $param
     *
     * @throws DependencyInjectionException
     */
    private function assertInterfaceIsConfigured(ReflectionParameter $param)
    {
        if (!array_key_exists($param->getClass()->getName(), $this->config->interfaces())) {
            throw new DependencyInjectionException(
                sprintf(
                    "Parameter %d ('%s') in %s cannot be instanciated (interface not configured)",
                    $param->getPosition(),
                    $param->getName(),
                    $param->getDeclaringClass()->getName()
                )
            );
        }
    }

    /**
     * @param ReflectionParameter $param
     * @param $reflectionClass
     *
     * @throws DependencyInjectionException
     */
    private function assertConfiguredClassImplementsInterface(ReflectionParameter $param, ReflectionClass $reflectionClass)
    {
        if (!$reflectionClass->implementsInterface($param->getClass()->getName())) {
            throw new DependencyInjectionException(
                sprintf(
                    "Parameter %d ('%s') in %s cannot be instanciated (configured class does implement interface)",
                    $param->getPosition(),
                    $param->getName(),
                    $param->getDeclaringClass()->getName()
                )
            );
        }
    }
}
