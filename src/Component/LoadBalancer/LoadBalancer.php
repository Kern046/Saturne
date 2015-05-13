<?php
/**
 * The LoadBalancer is an engine component
 * When a client does an handshake request to the engine,
 * An event is triggered, and the clientManager calls the LoadBalancer.
 * It affects that client to one of the running processes
 */
namespace Saturne\Component\LoadBalancer;

use Saturne\Model\Client;

use Saturne\Core\Kernel\EngineKernel;
use Saturne\Component\Event\EventManager;

/**
 * @name LoadBalancer
 * @author Axel Venet <axel-venet@developtech.fr>
 */
class LoadBalancer implements LoadBalancerInterface
{
    /** @var EngineKernel **/
    private $engine;
    
    public function __construct($engine)
    {
        $this->engine = $engine;
    }
    
    /**
     * {@inheritdoc}
     */
    public function affectClient(Client $client)
    {
        $this->engine->throwEvent(EventManager::CLIENT_AFFECTION, [
            'message' => "Client {$client->getIp()}:{$client->getPort()} is now affected"
        ]);
    }
}