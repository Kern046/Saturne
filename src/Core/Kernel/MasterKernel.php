<?php

namespace Saturne\Core\Kernel;

use Saturne\Component\Event\EventManager;
use Saturne\Component\Process\ProcessManager;
use Saturne\Component\Process\MasterGateway;
use Saturne\Component\Logger\CliLogger;
use Saturne\Component\Logger\FileLogger;
use Saturne\Component\LoadBalancer\LoadBalancer;
use Saturne\Component\Client\ClientManager;
use Saturne\Component\Memory\MemoryManager;
use Saturne\Component\Server\MasterServer;

/**
 * @name EngineKernel
 * @authro Axel Venet <axel-venet@developtech.fr>
 */
class MasterKernel extends AbstractKernel
{
    /**
     * {@inheritdoc}
     */
    public function init($options = [])
    {
        $this->setContainer();
        
        $this->container->set('saturne.event_manager', new EventManager($this));
        $this->container->set('saturne.memory_manager', new MemoryManager($this));
        $this->container->set('saturne.logger.cli', new CliLogger($this));
        $this->container->set('saturne.logger.file', new FileLogger($this));
        $this->container->set('saturne.process_manager', new ProcessManager($this));
        $this->container->set('saturne.process_gateway', new MasterGateway($this));
        $this->container->set('saturne.load_balancer', new LoadBalancer($this));
        $this->container->set('saturne.client_manager', new ClientManager($this));
        $this->container->set('saturne.server', new MasterServer($this));
        
        $this->setLoggers();
    }
    
    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $memoryManager = $this->container->get('saturne.memory_manager');
        $memoryManager->refreshMemory();
        
        $this->throwEvent(EventManager::ENGINE_INITIALIZED, [
            'message' => "Engine is now initialized with {$memoryManager->getMemory()}/{$memoryManager->getAllocatedMemory()} bytes"
        ]);
            
        $this->get('saturne.process_manager')->launchProcesses();
        $this->get('saturne.server')->listen();
    }
    
    /**
     * {@inheritdoc}
     */
    public function setLoggers()
    {
        $eventManager = $this->container->get('saturne.event_manager');
        if(PHP_SAPI === 'cli')
        {
            $eventManager->addListener($this->container->get('saturne.logger.cli'));
        }
        $eventManager->addListener($this->container->get('saturne.logger.file'));
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
}
