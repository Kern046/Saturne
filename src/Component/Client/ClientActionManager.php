<?php

namespace Saturne\Component\Client;

use Saturne\Core\Kernel\MasterKernel;

class ClientActionManager
{
    /** @var array **/
    private $actions = [
        'client:connect' => ['saturne.client_manager', 'createConnection'],
        'server:shutdown' => ['saturne.server', 'shutdown']
    ];
    /** @var MasterKernel **/
    private $engine;
    
    public function __construct($engine)
    {
        $this->engine = $engine;
    }
    
    public function treatAction($networkData)
    {
        $action = $networkData['action'];
        
        if(!isset($this->actions[$action]))
        {
            throw new \InvalidArgumentException('The requested action is not registered');
        }
        $this->engine->get($this->actions[$action][0])->{$this->actions[$action][1]}($networkData);
    }
}