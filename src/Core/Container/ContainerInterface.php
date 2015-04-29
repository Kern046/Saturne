<?php

namespace Saturne\Core\Container;

interface ContainerInterface
{
    /**
     * Set an item in the container
     * Throws exception if the name is already affected
     * 
     * @param string $name
     * @param mixed $item
     * @throws \InvalidArgumentException
     */
    public function set($name, $item);
    
    /**
     * Get an item from the container
     * Throws exception if the name is not affected
     * 
     * @param string $name
     * @return mixed $item
     * @throws \InvalidArgumentException
     */
    public function get($name);
    
    /**
     * Check if a name is affected
     * 
     * @param string $name
     * @return boolean
     */
    public function has($name);
}