<?php

namespace Saturne\Component\Client;

use Saturne\Core\Kernel\EngineKernel;

class ClientActionManager
{
    private $actions = [
        'client:connect' => ['saturne.client_manager', 'createConnection'],
        'server:shutdown' => ['saturne.server', 'shutdown']
    ];
    
    public function treatAction($networkData)
    {
        $action = $networkData['action'];
        
        if(!isset($this->actions[$action]))
        {
            throw new \InvalidArgumentException('The requested action is not registered');
        }
        EngineKernel::getInstance()->get($this->actions[$action][0])->{$this->actions[$action][1]}($networkData);
    }
}