<?php

namespace Saturne\Component\Logger;

/**
 * @name LoggerInterface
 * @author Axel Venet <axel-venet@developtech.fr>
 */
interface LoggerInterface
{
    /**
     * Log the message
     * 
     * @param array $data
     */
    public function log($data);
}