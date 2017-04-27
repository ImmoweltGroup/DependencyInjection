<?php

namespace ImmoweltHH\DependencyInjection;

class DependencyTree
{
    /** @var int */
    private $index;

    /** @var string[] */
    private $list;

    public function __construct()
    {
        $this->reset();
    }

    /**
     * @param string $className
     */
    public function addNode($className) {
        $this->list[$this->index] = $className;
        $this->index++;
    }

    public function killBottomNode() {
        unset($this->list[$this->index]);
        $this->index--;
    }

    /**
     * @param string $className
     *
     * @return bool
     */
    public function treeContains($className) {
        foreach ($this->list as $node) {
            if ($node === $className) {
                return true;
            }
        }

        return false;
    }

    public function reset() {
        $this->index = 0;
        $this->list = [];
    }

    public function toString()
    {
        return implode(" -> ", $this->list);
    }
}
