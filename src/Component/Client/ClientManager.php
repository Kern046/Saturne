<?php

namespace Saturne\Component\Client;

use Saturne\Core\Kernel\MasterKernel;

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
    /** @var MasterKernel **/
    private $engine;
    
    public function __construct($engine)
    {
        $this->engine = $engine;
        $engine->getContainer()->set('saturne.client_action_manager', new ClientActionManager($engine));
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
        
        $this->engine->throwEvent(EventManager::NETWORK_NEW_CONNECTION, [
            'message' => "New connection from {$data['ip']}:{$data['port']}"
        ]);
        
        $this->engine->get('saturne.load_balancer')->affectClient($client);
    }
}

