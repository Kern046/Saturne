<?php

namespace Saturne\Component\Process;

use Saturne\Core\Kernel\ProcessKernel;

/**
 * @name ProcessGateway
 * @author Axel Venet <axel-venet@developtech.fr>
 */
class ProcessGateway
{
    /** @var ProcessKernel **/
    private $engine;
    
    public function __construct($engine)
    {
        $this->engine = $engine;
    }
    /**
     * Send JSON data to the master
     * 
     * @param array $data
     */
    public function writeToMaster($data)
    {
        $process = $this->engine->getProcess();
        fwrite($process->getOutput(), json_encode(array_merge([
            'emmitter' => $process->getName(),
            'memory' => $process->getMemory(),
            'allocated-memory' => $process->getAllocatedMemory()
        ], $data)));
    }
    
    /**
     * Get data from the master pipe
     * 
     * @return array
     */
    public function readFromMaster()
    {
        $input = $this->engine->getProcess()->getInput();
        rewind($input);
        return json_decode(fgets($input), true);
    }
}