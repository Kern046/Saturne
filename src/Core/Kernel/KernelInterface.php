<?php

namespace Saturne\Core\Kernel;

/**
 * @name KernelInterface
 * @author Axel Venet <axel-venet@developtech.fr>
 */
interface KernelInterface
{
    /**
     * Initialize the EventManager
     */
    public function setEventManager();
    
    /**
     * Get the EventManager
     * 
     * @return EventManager
     */
    public function getEventManager();
    
    /**
     * Send an event to the EventManager for a broadcast diffusion to the listeners.
     * 
     * @param string $event
     * @param array $data
     */
    public function throwEvent($event, $data = []);
}