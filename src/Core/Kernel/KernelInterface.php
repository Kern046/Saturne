<?php

namespace Saturne\Core\Kernel;

use Saturne\Component\Client\ClientManager;
use Saturne\Component\Event\EventManager;
use Saturne\Component\Server\Server;
use Saturne\Component\LoadBalancer\LoadBalancer;
use Saturne\Component\Thread\ThreadManager;

/**
 * @name KernelInterface
 * @author Axel Venet <axel-venet@developtech.fr>
 */
interface KernelInterface
{
    /**
     * Initialize the engine and set all components
     */
    public function init();
    
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
     * Add loggers as listeners.
     * If the script is run by in CLI, a dedicated graphic logger is added
     */
    public function setLoggers();
    
    /**
     * Initialize the ThreadManager
     */
    public function setThreadManager();
    
    /**
     * Get the ThreadManager
     * 
     * @return ThreadManager
     */
    public function getThreadManager();
    
    /**
     * Add the LoadBalancer as a listener
     */
    public function setLoadBalancer();
    
    /**
     * Get the LoadBalancer
     * 
     * @return LoadBalancer
     */
    public function getLoadBalancer();
    
    /**
     * Set the clientManager and add ClientListener as an event listener
     */
    public function setClientManager();
    
    /**
     * Get the ClientManager
     * 
     * @return ClientManager
     */
    public function getClientManager();
    
    /**
     * Set the Server
     */
    public function setServer();
    
    /**
     * Get the Server
     * 
     * @return Server
     */
    public function getServer();
    
    /**
     * Send an event to the EventManager for a broadcast diffusion to the listeners.
     * 
     * @param string $event
     * @param array $data
     */
    public function throwEvent($event, $data = []);
    
    /**
     * Implementation of the Singleton design pattern
     * 
     * @return KernelInterface
     */
    public static function getInstance();
}