<?php

namespace Saturne\Component\Event;

trait EventListenerTrait
{
    /**
     * Check if the thrown event is registered for the current listener
     * 
     * @param string $event
     * @return boolean
     */
    public function supportsEvent($event)
    {
        return isset($this->events[$event]);
    }
    
    /**
     * Call the dedicated method for the given event
     * 
     * @param string $event
     * @param array $data
     * @return false|null
     */
    public function receiveEvent($event, $data)
    {
        if(!$this->supportsEvent($event))
        {
            return false;
        }
        $this->{$this->events[$event]}($data);
    }
}