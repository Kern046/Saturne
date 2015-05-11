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
    /** @var EngineKernel **/
    private $engine;
    
    public function __construct($engine)
    {
        $this->engine = $engine;
        $this->command =
            (PHP_OS === 'WINNT')
            ? 'start php ' . __DIR__ . '/../../launcher.php --target=thread'
            : 'php ' . __DIR__ . '/../../launcher.php --target=thread'
        ;
        $engine->getContainer()->set('saturne.thread_gateway', new ThreadGateway($engine));
    }
    
    public function __destruct()
    {
        foreach($this->threads as $thread)
        {
            $this->removeThread($thread->getName(), "{$thread->getName()} shutdown");
        }
        $this->engine->throwEvent(EventManager::NETWORK_THREADS_CLEARED, [
            'message' => 'Threads are now cleared'
        ]);
    }
    
    public function launchThreads()
    {
        $init = true;
        
        $memoryManager = $this->engine->get('saturne.memory_manager');
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
        
        $data = json_decode(fread($this->threads[$name]->getOutput(), 1024), true);
        
        $this->threads[$name]
            ->setMemory($data['memory'])
            ->setAllocatedMemory($data['allocated-memory'])
        ;
        
        return $this->threads[$name];
    }
    
    public function treatThreadInput($name)
    {
        $thread = $this->threads[$name];
        $contents = fread($thread->getInput(), 4096);
        
        if(empty($contents))
        {
            $this->removeThread($name, "$name is not running anymore");
        }
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
        ++$this->instanciedThreads;
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
        
        $server = $this->engine->get('saturne.server');
        $server->addInput($name, $pipes[0]);
        $server->addOutput($name, $pipes[1]);
        
        $this->engine->throwEvent(EventManager::NETWORK_NEW_THREAD, [
            'message' => "$name is now initialized"
        ]);
        return $name;
    }
    
    /**
     * Delete a thread
     * The reason is the message which will appear in the logs
     * 
     * @param string $name
     * @param string $reason
     * @throws \InvalidArgumentException
     */
    public function removeThread($name, $reason, $shutdown = false)
    {
        if(!isset($this->threads[$name]))
        {
            throw new \InvalidArgumentException("The given thread $name doesn't exist.");
        }
        if($shutdown)
        {
            $this->shutdownThread($this->threads[$name]);
        }
        $server = $this->engine->get('saturne.server');
        $server->removeInput($name);
        $server->removeOutput($name);
        
        fclose($this->threads[$name]->getInput());
        fclose($this->threads[$name]->getOutput());
        proc_close($this->threads[$name]->getProcess());
        unset($this->threads[$name]);
        
        $this->engine->throwEvent(EventManager::NETWORK_THREAD_SHUTDOWN, [
            'message' => $reason
        ]);
    }
    
    /**
     * Shutdown a running thread
     * 
     * @param Thread $thread
     */
    public function shutdownThread(Thread $thread)
    {
        $this->engine->get('saturne.thread_gateway')->writeTo($thread, [
            'command' => 'shutdown'
        ]);
    }
}