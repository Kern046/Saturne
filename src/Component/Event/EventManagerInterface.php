<?php

namespace Saturne\Component\Event;

/**
 * @name EventManagerInterface
 * @author Axel Venet <axel-venet@developtech.fr>
 */
interface EventManagerInterface
{
    /**
     * Send a broadcast call to the event listeners
     * 
     * @param string $event
     * @param array $data
     */
    public function transmit($event, $data);
    
    /**
     * Add an object implementing the EventListenerInterface to the listeners
     * 
     * @param object $listener
     * @throws \InvalidArgumentException
     */
    public function addListener($listener);
    
    /**
     * Remove an object from the current listeners
     * 
     * @param object $listener
     * @throws \InvalidArgumentException
     */
    public function removeListener($listener);
}