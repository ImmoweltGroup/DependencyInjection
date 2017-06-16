<?php

namespace ImmoweltHH\DependencyInjection;

use ImmoweltHH\DependencyInjection\Exception\CircularReferenceException;
use ImmoweltHH\DependencyInjection\Exception\DependencyInjectionException;
use ReflectionClass;
use ReflectionParameter;

class InjectionContainer
{

    /** @var string[] */
    private $aliases = [];

    /** @var object[] */
    private $cache = [];

    /** @var string[] */
    private $circularReferenceCache;

    /** @var DependencyTree */
    private $dependencyTree;

    /** @var bool */
    private $debug;

    public function __construct($debug = false)
    {
        $this->debug = $debug;
        $this->circularReferenceCache = [];
        $this->dependencyTree = new DependencyTree();
    }

    public static function builder() {
        return new InjectionContainerBuilder(new InjectionContainer());
    }

    /**
     * @param string $alias
     * @param string $className
     *
     * @throws DependencyInjectionException
     */
    public function registerAlias($alias, $className)
    {
        if (!class_exists($className)) {
            throw new DependencyInjectionException(
                sprintf("Class '%s' does not exist", $className)
            );
        }

        $this->aliases[$alias] = $className;
    }

    /**
     * @param object $object
     */
    public function registerPreConfiguredClass($object)
    {
        $name = (new ReflectionClass($object))->getName();
        $this->cache[$name] = $object;
    }

    /**
     * @param string $alias
     *
     * @return bool
     */
    public function hasAlias($alias)
    {
        return isset($this->aliases[$alias]);
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
            return $this->get($this->aliases[$alias]);
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
     * @param bool $value
     */
    public function setDebug($value)
    {
        $this->debug = $value;
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
        if ($param->getClass() === null) {
            throw new DependencyInjectionException(
                sprintf(
                    "Parameter %d ('%s') in %s cannot be instanciated",
                    $param->getPosition(),
                    $param->getName(),
                    $param->getDeclaringClass()->getName()
                )
            );
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
        if ($this->debug) {
            echo $this->dependencyTree->toString() . PHP_EOL;
        }
    }
}
