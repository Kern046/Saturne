<?php

namespace Saturne\Component\Thread;

use Saturne\Model\Thread;

use Saturne\Component\Thread\ThreadGateway;

use Saturne\Core\Kernel\EngineKernel;

use Saturne\Component\Event\EventManager;

/**
 * @name ThreadManager
 * @author Axel Venet <axel-venet@developtech.fr>
 */
class ThreadManager
{
    /** @var array<Thread> **/
    private $threads = [];
    /** @var integer **/
    private $instanciedThreads = 0;
    /** @var string **/
    private $command;
    /** @var ThreadGateway **/
    private $gateway;
    
    public function __construct()
    {
        $this->command =
            (PHP_OS === 'WINNT')
            ? 'start php launcher.php --target=thread'
            : 'php launcher.php --target=thread'
        ;
        $this->gateway = new ThreadGateway();
    }
    
    public function __destruct()
    {
        foreach($this->threads as $thread)
        {
            fclose($thread->getInput());
            fclose($thread->getOutput());
            proc_close($thread->getProcess());
            unset($thread);
        }
        EngineKernel::getInstance()->throwEvent(EventManager::NETWORK_THREADS_CLEARED, [
            'message' => 'Threads are now cleared'
        ]);
    }
    
    public function launchThreads()
    {
        $init = true;
        
        $memoryManager = EngineKernel::getInstance()->getMemoryManager();
        $memoryManager->refreshMemory();
        
        $totalMemory = $memoryManager->getAllocatedMemory();
        
        while($init === true)
        {
            $thread = $this->initThread();
            $totalMemory += $thread->getAllocatedMemory();
            
            if($totalMemory > 2000000)
            {
                $init = false;
            }
        }
    }
    
    public function initThread()
    {
        $name = $this->addThread();
        
        $contents = fread($this->threads[$name]->getOutput(), 1024);
        var_dump($contents);die();
    }
    
    public function treatThreadInput($name)
    {
        $thread = $this->threads[$name];
        $contents = fread($thread->getInput(), 4096);
        var_dump($contents);
    }
    
    /**
     * @return array<Thread>
     */
    public function getThreads()
    {
        return $this->threads;
    }
    
    public function addThread()
    {
        $name = "Thread_{$this->instanciedThreads}";
        
        $process = proc_open("{$this->command} --thread=$name", [
            0 => ['pipe', 'r'],
            1 => ['pipe', 'w'],
            2 => [
                'file',
                dirname(dirname(dirname(__DIR__))) .
                DIRECTORY_SEPARATOR . 'logs' .
                DIRECTORY_SEPARATOR . 'thread_errors.log',
                'a+'
            ]
        ], $pipes);
        
        $this->threads[$name] =
            (new Thread())
            ->setName($name)
            ->setProcess($process)
            ->setInput($pipes[0])
            ->setOutput($pipes[1])
        ;
        
        $server = EngineKernel::getInstance()->getServer();
        $server->addInput($name, $pipes[0]);
        $server->addOutput($name, $pipes[1]);
        ++$this->instanciedThreads;
        return $name;
    }
    
    /**
     * Delete a thread
     * 
     * @param string $name
     * @throws \InvalidArgumentException
     */
    public function removeThread($name, $shutdown = false)
    {
        if(!isset($this->threads[$name]))
        {
            throw new \InvalidArgumentException("The given thread $name doesn't exist.");
        }
        if($shutdown)
        {
            $this->shutdownThread($this->threads[$name]);
        }
        unset($this->threads[$name]);
    }
    
    /**
     * Shutdown a running thread
     * 
     * @param Thread $thread
     */
    public function shutdownThread(Thread $thread)
    {
        $this->gateway->writeTo($thread, [
            'command' => 'shutdown'
        ]);
    }
}