<?php

namespace Saturne\Component\Event;

/**
 * Interface to implement for an object used to be an event listener.
 * 
 * @name EventListenerInterface
 * @author Axel Venet <axel-venet@developtech.fr>
 */
interface EventListenerInterface
{
    /**
     * Check if the thrown event is registered for the current listener
     * 
     * @param string $event
     * @return boolean
     */
    public function supportsEvent($event);
    
    /**
     * Call the dedicated method for the given event
     * 
     * @param string $event
     * @param array $data
     */
    public function receiveEvent($event, $data);
}