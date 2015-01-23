<?php

namespace Saturne\Component\Memory;

/**
 * @name MemoryManagerInterface
 * @author Axel Venet <axel-venet@developtech.fr>
 */
interface MemoryManagerInterface
{
    /**
     * Actualize informations about the memory used by the Engine
     * and the memory allocated to PHP
     */
    public function refreshMemory();
    
    /**
     * Return the amount of memory used by the current program
     * 
     * @return integer
     */
    public function getMemory();
    
    /**
     * Return the memory allocated by the system to PHP
     * 
     * @return integer
     */
    public function getAllocatedMemory();
}