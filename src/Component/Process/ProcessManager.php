<?php

namespace Saturne\Component\Process;

use Saturne\Model\Process;

use Saturne\Core\Kernel\MasterKernel;

use Saturne\Component\Event\EventManager;

/**
 * @name ProcessManager
 * @author Axel Venet <axel-venet@developtech.fr>
 */
class ProcessManager
{
    /** @var array<Process> **/
    private $processes = [];
    /** @var integer **/
    private $instanciedProcesses = 0;
    /** @var string **/
    private $command;
    /** @var MasterKernel **/
    private $engine;
    
    public function __construct($engine)
    {
        $this->engine = $engine;
        $this->command =
            (PHP_OS === 'WINNT')
            ? 'start php ' . __DIR__ . '/../../launcher.php --target=process'
            : 'php ' . __DIR__ . '/../../launcher.php --target=process'
        ;
    }
    
    public function __destruct()
    {
        foreach($this->processes as $process)
        {
            $this->removeProcess($process->getName(), "{$process->getName()} shutdown");
        }
        $this->engine->throwEvent(EventManager::NETWORK_PROCESSES_CLEARED, [
            'message' => 'Processes are now cleared'
        ]);
    }
    
    public function launchProcesses()
    {
        $init = true;
        
        $memoryManager = $this->engine->get('saturne.memory_manager');
        $memoryManager->refreshMemory();
        
        $totalMemory = $memoryManager->getAllocatedMemory();
        
        while($init === true)
        {
            $process = $this->initProcess();
            $totalMemory += $process->getAllocatedMemory();
            if($totalMemory > 2000000)
            {
                $init = false;
            }
        }
    }
    
    public function initProcess()
    {
        $name = $this->addProcess();
        
        $data = json_decode(fread($this->processes[$name]->getOutput(), 1024), true);
        
        $this->processes[$name]
            ->setMemory($data['memory'])
            ->setAllocatedMemory($data['allocated-memory'])
        ;
        
        return $this->processes[$name];
    }
    
    public function treatProcessInput($name)
    {
        $process = $this->processes[$name];
        $contents = fread($process->getInput(), 4096);
        
        if(empty($contents))
        {
            $this->removeProcess($name, "$name is not running anymore");
        }
    }
    
    /**
     * @return array<Process>
     */
    public function getProcesses()
    {
        return $this->processes;
    }
    
    public function addProcess()
    {
        ++$this->instanciedProcesses;
        $name = "Process_{$this->instanciedProcesses}";
        
        $process = proc_open("{$this->command} --process=$name", [
            0 => ['pipe', 'r'],
            1 => ['pipe', 'w'],
            2 => [
                'file',
                dirname(dirname(dirname(__DIR__))) .
                DIRECTORY_SEPARATOR . 'logs' .
                DIRECTORY_SEPARATOR . 'process_errors.log',
                'a+'
            ]
        ], $pipes);
        
        $this->processes[$name] =
            (new Process())
            ->setName($name)
            ->setProcess($process)
            ->setInput($pipes[0])
            ->setOutput($pipes[1])
        ;
        
        $server = $this->engine->get('saturne.server');
        $server->addInput($name, $pipes[0]);
        $server->addOutput($name, $pipes[1]);
        
        $this->engine->throwEvent(EventManager::NETWORK_NEW_PROCESS, [
            'message' => "$name is now initialized"
        ]);
        return $name;
    }
    
    /**
     * Delete a process
     * The reason is the message which will appear in the logs
     * 
     * @param string $name
     * @param string $reason
     * @throws \InvalidArgumentException
     */
    public function removeProcess($name, $reason, $shutdown = false)
    {
        if(!isset($this->processes[$name]))
        {
            throw new \InvalidArgumentException("The given process $name doesn't exist.");
        }
        if($shutdown)
        {
            $this->shutdownProcess($this->processes[$name]);
        }
        $server = $this->engine->get('saturne.server');
        $server->removeInput($name);
        $server->removeOutput($name);
        
        fclose($this->processes[$name]->getInput());
        fclose($this->processes[$name]->getOutput());
        proc_close($this->processes[$name]->getProcess());
        unset($this->processes[$name]);
        
        $this->engine->throwEvent(EventManager::NETWORK_PROCESS_SHUTDOWN, [
            'message' => $reason
        ]);
    }
    
    /**
     * Shutdown a running process
     * 
     * @param Process $process
     */
    public function shutdownProcess(Process $process)
    {
        $this->engine->get('saturne.process_gateway')->writeTo($process, [
            'command' => 'shutdown'
        ]);
    }
}