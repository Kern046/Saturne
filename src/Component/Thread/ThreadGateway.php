<?php

namespace Saturne\Component\Thread;

use Saturne\Model\Thread;

/**
 * @name ThreadGateway
 * @author Axel Venet <axel-venet@developtech.fr>
 */
class ThreadGateway
{
    /**
     * Send JSON data to a thread
     * 
     * @param Thread $thread
     * @param array $data
     */
    public function writeTo(Thread $thread, $data)
    {
        fwrite($thread->getInput(), json_encode($data));
    }
    
    /**
     * Get data from a thread pipe
     * 
     * @param Thread $thread
     * @return array
     */
    public function read(Thread $thread)
    {
        $output = $thread->getOutput();
        rewind($output);
        return json_decode(fgets($output), true);
    }
}