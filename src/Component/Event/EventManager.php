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
    
    const ENGINE_INITIALIZED = 'engine-initialized';
    const ENGINE_STATUS_REQUEST = 'engine-status-request';
    
    const NETWORK_SERVER_LISTENING = 'network-server-listening';
    const NETWORK_NEW_CONNECTION = 'network-new-connection';
    
    /**
     * {@inheritdoc}
     */
    public function transmit($event, $data)
    {
        reset($this->listeners);
        
        while($name = key($this->listeners))
        {
            $this->listeners[$name]->receiveEvent($event, $data);
            next($this->listeners);
        }
    }
    
    /**
     * {@inheritdoc}
     */
    public function addListener($listener)
    {
        if(!in_array('Saturne\Component\Event\EventListenerTrait', class_uses($listener)))
        {
            throw new \InvalidArgumentException('The listener must use EventListenerTrait');
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

