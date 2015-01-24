<?php

namespace Saturne\Component\Thread;

use Saturne\Model\Thread;

use Saturne\Component\Thread\ThreadGateway;

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
    
    /**
     * @return array<Thread>
     */
    public function getThreads()
    {
        return $this->threads;
    }
    
    public function addThread()
    {
        $name = 'Thread_' . $this->instanciedThreads;
        $command = $this->command . " $name";
        
        proc_close(proc_open($command, [
            0 => ['pipe', 'r'],
            1 => ['pipe', 'w'],
            2 => [
                    'file',
                    dirname(dirname(dirname(__DIR__))) .
                    DIRECTORY_SEPARATOR . 'logs' .
                    DIRECTORY_SEPARATOR . 'thread_errors.log',
                    'a+'
                ]
        ], $pipes));
        
        $this->threads[$name] = new Thread($name, $pipes[0], $pipes[1]);
        
        ++$this->instanciedThreads;
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