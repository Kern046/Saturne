<?php

namespace Saturne\Component\Client;

/**
 * @name ClientManagerInterface
 * @author Axel Venet <axel-venet@developtech.fr>
 */
interface ClientManagerInterface
{
    /**
     * Handle a new connection to the engine
     * 
     * @param array $data
     */
    public function createConnection($data);
}