<?php

namespace Saturne\Component\Client;

use Saturne\Core\Kernel\EngineKernel;

use Saturne\Model\Client;

use Saturne\Component\Client\ClientActionManager;
use Saturne\Component\Event\EventManager;

/**
 * @name ClientManager
 * @author Axel Venet <axel-venet@developtech.fr>
 */
class ClientManager implements ClientManagerInterface
{
    /** @var array **/
    private $clients;
    
    public function __construct()
    {
        EngineKernel::getInstance()->getContainer()->set('saturne.client_action_manager', new ClientActionManager());
    }
    
    public function cleanClients()
    {
        unset($this->clients);
    }
    
    /**
     * {@inheritdoc}
     */
    public function createConnection($data)
    {
        $client =
            (new Client())
            ->setIp($data['ip'])
            ->setPort($data['port'])
            ->setUserAgent($data['user-agent'])
        ;
        
        EngineKernel::getInstance()->throwEvent(EventManager::NETWORK_NEW_CONNECTION, [
            'message' => "New connection from {$data['ip']}:{$data['port']}"
        ]);
        
        EngineKernel::getInstance()->get('saturne.load_balancer')->affectClient($client);
    }
}

