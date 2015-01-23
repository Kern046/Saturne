<?php

namespace Saturne\Component\Memory;

use Saturne\Component\Event\EventManager;
use Saturne\Component\Event\EventListenerTrait;

/**
 * @name MemoryManager
 * @author Axel Venet <axel-venet@developtech.fr>
 */
class MemoryManager implements MemoryManagerInterface
{
    use EventListenerTrait;
    /** @var integer **/
    private $memory;
    /** @var integer **/
    private $allocatedMemory;
    /** @var array **/
    private $events = [
        EventManager::ENGINE_STATUS_REQUEST => 'refreshMemory'
    ];
    
    /**
     * {inheritdoc}
     */
    public function refreshMemory()
    {
        $this->memory = memory_get_usage();
        $this->allocatedMemory = memory_get_usage(true);
    }
    
    /**
     * {@inheritdoc}
     */
    public function getMemory()
    {
        return $this->memory;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getAllocatedMemory()
    {
        return $this->allocatedMemory;
    }
}