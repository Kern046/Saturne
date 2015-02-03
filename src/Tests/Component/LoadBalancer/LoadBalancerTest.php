<?php

namespace Saturne\Tests\Component\LoadBalancer;

use Saturne\Component\LoadBalancer\LoadBalancer;

class LoadBalancerTest extends \PHPUnit_Framework_TestCase
{
    /** @var LoadBalancer **/
    private $loadBalancer;
    
    public function setUp()
    {
        $this->loadBalancer = new LoadBalancer();
    }
    
    public function testBalance()
    {
        
    }
}