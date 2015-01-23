<?php

namespace Saturne\Core\Kernel;

use Saturne\Component\Event\EventManager;

use Saturne\Component\Logger\CliLogger;
use Saturne\Component\Logger\FileLogger;

/**
 * @name EngineKernel
 * @authro Axel Venet <axel-venet@developtech.fr>
 */
class EngineKernel implements KernelInterface
{
    /** @var EventManager **/
    private $eventManager;
    
    public function __construct()
    {
        $this->setEventManager();
        $this->setLoggers();
        
        $this->throwEvent(EventManager::ENGINE_INITIALIZED, [
            'message' => 'Engine is now initialized'
        ]);
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
    public function setLoggers()
    {
        if(PHP_SAPI === 'cli')
        {
            $this->eventManager->addListener(new CliLogger());
        }
        $this->eventManager->addListener(new FileLogger());
    }
    
    /**
     * {@inheritdoc}
     */
    public function throwEvent($event, $data = [])
    {
        $this->eventManager->transmit($event, $data);
    }
}
