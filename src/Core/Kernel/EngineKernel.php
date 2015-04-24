<?php

namespace Saturne\Core\Kernel;

use Saturne\Component\Event\EventManager;

use Saturne\Component\Thread\ThreadManager;

use Saturne\Component\Logger\CliLogger;
use Saturne\Component\Logger\FileLogger;

use Saturne\Component\LoadBalancer\LoadBalancer;

use Saturne\Component\Client\ClientManager;

use Saturne\Component\Memory\MemoryManager;

use Saturne\Component\Server\Server;

/**
 * @name EngineKernel
 * @authro Axel Venet <axel-venet@developtech.fr>
 */
class EngineKernel implements KernelInterface
{
    /** @var EngineKernel **/
    private static $instance;
    /** @var EventManager **/
    private $eventManager;
    /** @var ThreadManager **/
    private $threadManager;
    /** @var LoadBalancer **/
    private $loadBalancer;
    /** @var ClientManager **/
    private $clientManager;
    /** @var MemoryManager **/
    private $memoryManager;
    /** @var Server **/
    private $server;
    
    private function __construct(){}
    
    /**
     * {@inheritdoc}
     */
    public function init()
    {
        $this->setMemoryManager();
        $this->setEventManager();
        $this->setLoggers();
        $this->setThreadManager();
        $this->setLoadBalancer();
        $this->setClientManager();
        $this->setServer();
        $this->memoryManager->refreshMemory();
        
        $this->throwEvent(EventManager::ENGINE_INITIALIZED, [
            'message' => "Engine is now initialized with {$this->memoryManager->getMemory()}/{$this->memoryManager->getAllocatedMemory()} bytes"
        ]);
        
        $this->threadManager->launchThreads();
        $this->server->listen();
    }
    
    public function shutdown()
    {
        $this->clientManager->cleanClients();
        $this->throwEvent(EventManager::ENGINE_SHUTDOWN, [
            'message' => 'Engine will now shutdown'
        ]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function setMemoryManager()
    {
        $this->memoryManager = new MemoryManager();
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
    public function getEventManager()
    {
        return $this->eventManager;
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
    public function getThreadManager()
    {
        return $this->threadManager;
    }
    
    /**
     * {@inheritdoc}
     */
    public function setServer()
    {
        $this->server = new Server();
    }
    
    /**
     * {@inheritdoc}
     */
    public function getMemoryManager()
    {
        return $this->memoryManager;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getServer()
    {
        return $this->server;
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
    public function getLoadBalancer()
    {
        return $this->loadBalancer;
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
    public function setClientManager()
    {
        $this->clientManager = new ClientManager();
    }
    
    /**
     * {@inheritdoc}
     */
    public function getClientManager()
    {
        return $this->clientManager;
    }
    
    /**
     * {@inheritdoc}
     */
    public function throwEvent($event, $data = [])
    {
        $this->eventManager->transmit($event, $data);
    }
    
    public static function getInstance()
    {
        if(self::$instance === null)
        {
            self::$instance = new self();
        }
        return self::$instance;
    }
}
