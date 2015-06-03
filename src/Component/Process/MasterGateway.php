<?php

namespace Saturne\Component\Process;

use Saturne\Model\Process;

/**
 * @name ProcessGateway
 * @author Axel Venet <axel-venet@developtech.fr>
 */
class MasterGateway
{
    /** @var EngineKernel **/
    private $engine;
    
    public function __construct($engine)
    {
        $this->engine = $engine;
    }
    /**
     * Send JSON data to a process
     * 
     * @param Process $process
     * @param array $data
     */
    public function writeTo(Process $process, $data)
    {
        fwrite($process->getInput(), json_encode($data));
    }
    
    /**
     * Get data from a process pipe
     * 
     * @param Process $process
     * @return array
     */
    public function read(Process $process, $bufferSize = 2048)
    {
        return json_decode(fread($process->getOutput(), $bufferSize), true);
    }
}