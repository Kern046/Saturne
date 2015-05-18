<?php

namespace Saturne\Core\Kernel;

/**
 * @name KernelInterface
 * @author Axel Venet <axel-venet@developtech.fr>
 */
interface KernelInterface
{
    /**
     * Initialize the engine and set all components in the container
     */
    public function init();
    
    /**
     * Run the engine
     */
    public function run($options = []);
    
    /**
     * Shutdown the engine
     */
    public function shutdown();
    
    /**
     * Set the container
     */
    public function setContainer();
    
    /**
     * Get the container
     * 
     * @return \Saturne\Core\Container\KernelContainer
     */
    public function getContainer();
    
    /**
     * Shorcut to access a container item
     * 
     * @param string $name
     * @return mixed
     * @throws \InvalidArgumentException
     */
    public function get($name);
    
    /**
     * Add loggers as listeners.
     * If the script is run by in CLI, a dedicated graphic logger is added
     */
    public function setLoggers();
    
    /**
     * Send an event to the EventManager for a broadcast diffusion to the listeners.
     * 
     * @param string $event
     * @param array $data
     */
    public function throwEvent($event, $data = []);
}