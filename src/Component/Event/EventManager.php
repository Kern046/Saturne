<?php

namespace Saturne\Component\Event;

/**
 * @name EventManager
 * @author Axel Venet <axel-venet@developtech.fr>
 */
class EventManager implements EventManagerInterface
{
    /** @var array <Object> **/
    private $listeners = [];
    
    const ENGINE_INITIALIZED = 'engine-initialized';
    const ENGINE_STATUS_REQUEST = 'engine-status-request';
    const ENGINE_SHUTDOWN = 'engine-shutdown';
    
    const NETWORK_SERVER_LISTENING = 'network-server-listening';
    const NETWORK_NEW_CONNECTION = 'network-new-connection';
    const NETWORK_SHUTDOWN = 'network-shutdown';
    
    const NETWORK_NEW_PROCESS = 'network-new-process';
    const NETWORK_PROCESS_LISTENING = 'network-process-listening';
    const NETWORK_PROCESS_SHUTDOWN = 'network-process-shutdown';
    const NETWORK_PROCESSES_CLEARED = 'network-processes-cleared';
    
    const CLIENT_AFFECTION = 'client-affection';
    
    /** @var EngineKernel **/
    private $engine;
    
    public function __construct($engine)
    {
        $this->engine = $engine;
    }
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

