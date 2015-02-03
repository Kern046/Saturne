<?php
/**
 * The LoadBalancer is an engine component
 * When a client does an handshake request to the engine,
 * An event is triggered, and the clientManager calls the LoadBalancer.
 * It affects that client to one of the running threads
 */
namespace Saturne\Component\LoadBalancer;

/**
 * @name LoadBalancer
 * @author Axel Venet <axel-venet@developtech.fr>
 */
class LoadBalancer implements LoadBalancerInterface
{
    public function __construct()
    {
        
    }
    
    /**
     * {@inheritdoc}
     */
    public function affectClient()
    {
        
    }
}