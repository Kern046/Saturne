<?php

namespace Saturne\Component\Event;

/**
 * @name EventManager
 * @author Axel Venet <axel-venet@developtech.fr>
 */
class EventManager implements EventManagerInterface
{
    /** @var array <Object> **/
    private $listeners;
    
    /**
     * {@inheritdoc}
     */
    public function transmit($event)
    {
        reset($this->listeners);
        
        while($name = key($this->listeners))
        {
            $this->listeners[$name]->receiveEvent($event);
            next($this->listeners);
        }
    }
    
    /**
     * {@inheritdoc}
     */
    public function addListener($listener)
    {
        if(!$listener instanceof EventListenerInterface)
        {
            throw new \InvalidArgumentException('The listener must implement EventListenerInterface');
        }
        $this->listeners[spl_object_hash($listener)] = $listener;
    }
    
    /**
     * {@inheritdoc}
     */
    public function removeListener($listener)
    {
        $hashID = spl_object_hash($listener);
        if(!isset($this->listeners[$hashID]))
        {
            throw new \InvalidArgumentException('Listener to remove is not stored');
        }
        unset($this->listeners[$hashID]);
    }
}

