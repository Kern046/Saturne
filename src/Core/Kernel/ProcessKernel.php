<?php

namespace Saturne\Core\Kernel;

use Saturne\Component\Event\EventManager;
use Saturne\Component\Process\ProcessGateway;
use Saturne\Component\Logger\FileLogger;
use Saturne\Component\Client\ClientManager;
use Saturne\Component\Memory\MemoryManager;
use Saturne\Component\Server\ProcessServer;

use Saturne\Model\Process;

class ProcessKernel extends AbstractKernel
{
    /** @var Process **/
    private $process;
    /**
     * {@inheritdoc}
     */
    public function init()
    {
        $this->setContainer();
        
        $this->container->set('saturne.event_manager', new EventManager($this));
        $this->container->set('saturne.memory_manager', new MemoryManager($this));
        $this->container->set('saturne.process_gateway', new ProcessGateway($this));
        $this->container->set('saturne.logger.file', new FileLogger($this));
        $this->container->set('saturne.client_manager', new ClientManager($this));
        $this->container->set('saturne.server', new ProcessServer($this));
        
        $this->setLoggers();
    }
    
    /**
     * {@inheritdoc}
     */
    public function run($options = [])
    {
        $memoryManager = $this->container->get('saturne.memory_manager');
        $memoryManager->refreshMemory();
        
        $this->process =
            (new Process())
            ->setName($options['process'])
            ->setInput(STDIN)
            ->setOutput(STDOUT)
            ->setMemory($memoryManager->getMemory())
            ->setAllocatedMemory($memoryManager->getAllocatedMemory())
        ;
        
        $this->container->get('saturne.process_gateway')->writeToMaster([]);
        
        $this->get('saturne.server')->listen();
    }
    
    /**
     * {@inheritdoc}
     */
    public function shutdown()
    {
        $this->container->get('saturne.process_gateway')->writeToMaster([]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function throwEvent($event, $data = [])
    {
        
    }
    
    /**
     * {@inheritdoc}
     */
    public function setLoggers()
    {
        
    }
    
    /**
     * @return Process
     */
    public function getProcess()
    {
        return $this->process;
    }
}