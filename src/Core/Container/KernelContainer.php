<?php

namespace Saturne\Core\Container;

class KernelContainer implements ContainerInterface
{
    /** @var array **/
    protected $items;
    
    public function set($name, $item)
    {
        if($this->has($name))
        {
            throw new \InvalidArgumentException("$name is already affected");
        }
        $this->items[$name] = $item;
    }
    
    public function get($name)
    {
        if(!$this->has($name))
        {
            throw new \InvalidArgumentException("$name is not affected");
        }
        return $this->items[$name];
    }
    
    public function has($name)
    {
        return isset($this->items[$name]);
    }
}