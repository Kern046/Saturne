<?php

namespace Saturne\Component\Process;

use Saturne\Model\Process;

use Saturne\Core\Kernel\MasterKernel;

use Saturne\Component\Event\EventManager;

/**
 * @name ProcessManager
 * @author Axel Venet <axel-venet@developtech.fr>
 */
class MasterManager
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
        $process = $this->addProcess();
        
        if(!$this->handshake($process))
        {
            $name = $process->getName();
            $this->removeProcess($name, "$name failed to launch");
            return true;
        }
        
        return $process;
    }
    
    public function handshake(Process &$process)
    {
        $gateway = $this->engine->get('saturne.process_gateway');
        $output = $process->getOutput();
        
        stream_set_blocking($output, 1);
        
        $data = $gateway->read($process);
        
        $gateway->writeTo($process, [
            'protocol' => 'tcp',
            'address' => $this->getFreeServerAddress()
        ]);
        
        $response = $gateway->read($process);
        
        $this->engine->throwEvent(EventManager::NETWORK_PROCESS_LISTENING, [
            'message' => $response['message'],
            'emitter' => $response['emmitter']
        ]);
        
        stream_set_blocking($output, 0);
        
        $process->setMemory($response['memory']);
        $process->setAllocatedMemory($response['allocated-memory']);
        
        return true;
    }
    
    public function getFreeServerAddress()
    {
        return '0.0.0.0:8890';
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
    
    /**
     * @return Process
     */
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
        return $this->processes[$name];
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