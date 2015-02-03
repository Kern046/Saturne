<?php

namespace Saturne\Core\Kernel;

use Saturne\Component\Event\EventManager;

use Saturne\Component\Thread\ThreadManager;

use Saturne\Component\Logger\CliLogger;
use Saturne\Component\Logger\FileLogger;

use Saturne\Component\LoadBalancer\LoadBalancer;

/**
 * @name EngineKernel
 * @authro Axel Venet <axel-venet@developtech.fr>
 */
class EngineKernel implements KernelInterface
{
    /** @var EventManager **/
    private $eventManager;
    /** @var ThreadManager **/
    private $threadManager;
    /** @var LoadBalancer **/
    private $loadBalancer;
    
    public function __construct()
    {
        $this->setEventManager();
        $this->setLoggers();
        $this->setThreadManager();
        $this->setLoadBalancer();
        
        $this->throwEvent(EventManager::ENGINE_INITIALIZED, [
            'message' => 'Engine is now initialized'
        ]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function setEventManager()
    {
        $this->eventManager = new EventManager();
    }
    
    /**
     * {@inheritdoc}
     */
    public function setThreadManager()
    {
        $this->threadManager = new ThreadManager();
    }
    
    /**
     * {@inheritdoc}
     */
    public function setLoadBalancer()
    {
        $this->loadBalancer = new LoadBalancer();
    }
    
    /**
     * {@inheritdoc}
     */
    public function getEventManager()
    {
        return $this->eventManager;
    }
    
    /**
     * {@inheritdoc}
     */
    public function setLoggers()
    {
        if(PHP_SAPI === 'cli')
        {
            $this->eventManager->addListener(new CliLogger());
        }
        $this->eventManager->addListener(new FileLogger());
    }
    
    /**
     * {@inheritdoc}
     */
    public function throwEvent($event, $data = [])
    {
        $this->eventManager->transmit($event, $data);
    }
}
