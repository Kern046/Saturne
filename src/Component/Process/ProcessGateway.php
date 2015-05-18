<?php

namespace Saturne\Component\Process;

/**
 * @name ProcessGateway
 * @author Axel Venet <axel-venet@developtech.fr>
 */
class ProcessGateway
{
    /**
     * Send JSON data to the master
     * 
     * @param resource $output
     * @param array $data
     */
    public function writeTo($output, $data)
    {
        fwrite($output, json_encode($data));
    }
    
    /**
     * Get data from the master pipe
     * 
     * @param resource $input
     * @return array
     */
    public function read($input)
    {
        rewind($input);
        return json_decode(fgets($input), true);
    }
}