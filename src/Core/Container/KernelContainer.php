<?php

namespace Saturne\Core\Container;

class KernelContainer implements ContainerInterface
{
    /** @var array **/
    protected $items = [];
    
    /**
     * {@inheritdoc}
     */
    public function set($name, $item)
    {
        $this->items[$name] = $item;
    }
    
    /**
     * {@inheritdoc}
     */
    public function get($name)
    {
        if(!$this->has($name))
        {
            throw new \InvalidArgumentException("$name is not affected");
        }
        return $this->items[$name];
    }
    
    /**
     * {@inheritdoc}
     */
    public function has($name)
    {
        return isset($this->items[$name]);
    }
}