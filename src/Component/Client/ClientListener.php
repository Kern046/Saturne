<?php

namespace Saturne\Component\Client;

use Saturne\Component\Event\EventListenerTrait;
use Saturne\Component\Event\EventManager;

use Saturne\Core\Kernel\EngineKernel;

class ClientListener
{
    use EventListenerTrait;
    
    /** @var array **/
    private $events = [
        EventManager::NETWORK_NEW_CONNECTION => 'createConnection'
    ];
    
    public function newConnection($data)
    {
        $engine = EngineKernel::getInstance();
        $engine->getClientManager()->createConnection($data);
    }
}