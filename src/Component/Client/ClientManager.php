<?php

namespace Saturne\Component\Client;

use Saturne\Core\Kernel\EngineKernel;

use Saturne\Model\Client;

/**
 * @name ClientManager
 * @author Axel Venet <axel-venet@developtech.fr>
 */
class ClientManager implements ClientManagerInterface
{
    /**
     * {@inheritdoc}
     */
    public function createConnection($data)
    {
        $networkData = $this->getNetworkData($data);
        
        $client =
            (new Client())
            ->setIp($networkData['ip'])
            ->setPort($networkData['port'])
            ->setUserAgent($networkData['userAgent'])
        ;
        
        $loadBalancer = EngineKernel::getInstance()->getLoadBalancer();
        $loadBalancer->affectClient($client);
    }
    
    /**
     * {@inheritdoc}
     */
    public function getNetworkData($data)
    {
        // TODO : Implement network operations on the request
        return [
            'ip' => '127.0.0.1',
            'port' => 4000,
            'userAgent' => 'Mozilla'
        ];
    }
}

