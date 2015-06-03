<?php

namespace Saturne\Core\Kernel;

use Saturne\Component\Event\EventManager;
use Saturne\Component\Process\ProcessGateway;
use Saturne\Component\Process\ProcessManager;
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
    public function init($options = [])
    {
        $this->setContainer();
        
        $this->container->set('saturne.event_manager', new EventManager($this));
        $this->container->set('saturne.memory_manager', new MemoryManager($this));
        $this->container->set('saturne.process_manager', new ProcessManager($this));
        $this->container->set('saturne.process_gateway', new ProcessGateway($this));
        $this->container->set('saturne.logger.file', new FileLogger($this));
        $this->container->set('saturne.client_manager', new ClientManager($this));
        $this->container->set('saturne.process_server', new ProcessServer($this));
        
        $this->container->get('saturne.memory_manager')->refreshMemory();
        $this->setProcess($options);
        
        $this->setLoggers();
    }
    
    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $this->get('saturne.process_manager')->handshake();
        $this->get('saturne.process_server')->listen();
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
        $logger = $this->container->get('saturne.logger.file');
        $logger->setFile($this->process->getName());
        
        $this->container->get('saturne.event_manager')->addListener($logger);
    }
    
    public function setProcess($options)
    {
        $memoryManager = $this->container->get('saturne.memory_manager');
        $this->process =
            (new Process())
            ->setName($options['process'])
            ->setInput(STDIN)
            ->setOutput(STDOUT)
            ->setMemory($memoryManager->getMemory())
            ->setAllocatedMemory($memoryManager->getAllocatedMemory())
        ;
    }
    
    /**
     * @return Process
     */
    public function getProcess()
    {
        return $this->process;
    }
}