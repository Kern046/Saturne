<?php

namespace Saturne\Core\Kernel;

use Saturne\Manager\EventManager;

/**
 * @name EngineKernel
 * @authro Axel Venet <axel-venet@developtech.fr>
 */
class EngineKernel implements KernelInterface
{
    private $eventManager;
    
    public function __construct()
    {
        $this->setEventManager();
    }
    
    /**
     * {@inheritdoc}
     */
    public function setEventManager()
    {
        $this->eventManager = new EventManager();
    }
    
    /**
     * {@inheritdoc}
     */
    public function getEventManager()
    {
        return $this->eventManager;
    }
    
    /**
     * {@inheritdoc}
     */
    public function throwEvent($event)
    {
        $this->eventManager->transmit($event);
    }
}
