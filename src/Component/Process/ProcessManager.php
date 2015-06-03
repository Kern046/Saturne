<?php

namespace Saturne\Component\Process;

use Saturne\Core\Kernel\ProcessKernel;

class ProcessManager
{
    /** @var ProcessKernel **/
    private $engine;
    
    /**
     * @param ProcessKernel $engine
     */
    public function __construct($engine)
    {
        $this->engine = $engine;
    }
    
    public function handshake()
    {
        $gateway = $this->engine->get('saturne.process_gateway');
        
        $gateway->writeToMaster([]);
        
        $data = $gateway->readFromMaster();
        
        $address = "{$data['protocol']}://{$data['address']}";
        
        $process = $this->engine->getProcess();
        $process->setAddress($address);
        $process->refreshMemory();
        
        $gateway->writeToMaster([
            'message' => 'Connection to the master is confirmed, will now listen on ' . $address
        ]);
        
        $server = $this->engine->get('saturne.process_server');
        $server->listen($address);
    }
}