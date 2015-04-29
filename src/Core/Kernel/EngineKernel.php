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

use Saturne\Core\Container\KernelContainer;

/**
 * @name EngineKernel
 * @authro Axel Venet <axel-venet@developtech.fr>
 */
class EngineKernel implements KernelInterface
{
    /** @var EngineKernel **/
    private static $instance;
    /** @var KernelContainer **/
    private $container;
    
    private function __construct(){}
    
    /**
     * {@inheritdoc}
     */
    public function init()
    {
        $memoryManager = new MemoryManager();
        
        $this->setContainer();
        $this->container->set('saturne.memory_manager', $memoryManager);
        $this->container->set('saturne.event_manager', new EventManager());
        $this->container->set('saturne.logger.cli', new CliLogger());
        $this->container->set('saturne.logger.file', new FileLogger());
        $this->container->set('saturne.thread_manager', new ThreadManager());
        $this->container->set('saturne.load_balancer', new LoadBalancer());
        $this->container->set('saturne.client_manager', new ClientManager());
        $this->container->set('saturne.server', new Server());
        
        $this->setLoggers();
        
        
        
        $this->container->get('saturne.memory_manager')->refreshMemory();
        
        $this->throwEvent(EventManager::ENGINE_INITIALIZED, [
            'message' => "Engine is now initialized with {$memoryManager->getMemory()}/{$memoryManager->getAllocatedMemory()} bytes"
        ]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function setContainer()
    {
        $this->container = new KernelContainer();
    }
    
    /**
     * {@inheritdoc}
     */
    public function getContainer()
    {
        return $this->container;
    }
    
    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $this->get('saturne.thread_manager')->launchThreads();
        $this->get('saturne.server')->listen();
    }
    
    /**
     * {@inheritdoc}
     */
    public function get($name)
    {
        return $this->container->get($name);
    }
    
    /**
     * {@inheritdoc}
     */
    public function setLoggers()
    {
        $eventManager = $this->container->get('saturne.event_manager');
        if(PHP_SAPI === 'cli')
        {
            $eventManager->addListener(new CliLogger());
        }
        $eventManager->addListener(new FileLogger());
    }
    
    /**
     * {@inheritdoc}
     */
    public function shutdown()
    {
        $this->get('saturne.client_manager')->cleanClients();
        $this->throwEvent(EventManager::ENGINE_SHUTDOWN, [
            'message' => 'Engine will now shutdown'
        ]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function throwEvent($event, $data = [])
    {
        $this->get('saturne.event_manager')->transmit($event, $data);
    }
    
    /**
     * {@inheritdoc}
     */
    public static function getInstance()
    {
        if(self::$instance === null)
        {
            self::$instance = new self();
        }
        return self::$instance;
    }
}
