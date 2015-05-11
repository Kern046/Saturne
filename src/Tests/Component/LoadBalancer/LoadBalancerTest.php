<?php

namespace Saturne\Tests\Component\LoadBalancer;

use Saturne\Component\LoadBalancer\LoadBalancer;

class LoadBalancerTest extends \PHPUnit_Framework_TestCase
{
    /** @var LoadBalancer **/
    private $loadBalancer;
    
    public function setUp()
    {
        $this->loadBalancer = new LoadBalancer($this->getEngineMock());
    }
    
    public function testBalance()
    {
        $this->markTestIncomplete('Have to wait for implementation');
    }
    
    public function getEngineMock()
    {
        $engineMock = $this
            ->getMockBuilder('Saturne\Core\Kernel\EngineKernel')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        return $engineMock;
    }
}