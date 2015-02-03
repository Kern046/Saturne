<?php

namespace Saturne\Component\LoadBalancer;

use Saturne\Model\Client;

/**
 * @name LoadBalancerInterface
 * @author Axel Venet <axel-venet@developtech.fr>
 */
interface LoadBalancerInterface
{
    /**
     * This method compare the running threads status and choose one of them.
     * The given client will be attached to the chosen thread.
     */
    public function affectClient(Client $client);
}