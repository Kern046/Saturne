<?php

namespace Saturne\Tests\Component\Client;

use Saturne\Component\Client\ClientManager;

class ClientManagerTest extends \PHPUnit_Framework_TestCase
{
    /** @var ClientManager **/
    private $clientManager;
    
    public function setUp()
    {
        $this->clientManager = new ClientManager($this->getEngineMock());
    }
    
    public function testCreateConnection()
    {
        $result = $this->clientManager->createConnection([
            'ip' => '127.0.0.1',
            'port' => '8888',
            'user-agent' => 'Mozilla agent'
        ]);
    }
    
    public function testGetNetworkData()
    {
        $this->markTestIncomplete('Need to implement network operations');
    }
    
    public function getEngineMock()
    {
        $engineMock = $this
            ->getMockBuilder('Saturne\Core\Kernel\EngineKernel')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $engineMock
            ->expects($this->any())
            ->method('throwEvent')
            ->willReturn(true)
        ;
        $engineMock
            ->expects($this->any())
            ->method('getContainer')
            ->willReturnCallback([$this, 'getContainerMock'])
        ;
        $engineMock
            ->expects($this->any())
            ->method('get')
            ->willReturnCallback([$this, 'getLoadBalancerMock'])
        ;
        return $engineMock;
    }
    
    public function getContainerMock()
    {
        $containerMock = $this
            ->getMockBuilder('Saturne\Core\Container\KernelContainer')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $containerMock
            ->expects($this->any())
            ->method('set')
            ->willReturn(true)
        ;
        return $containerMock;
    }
    
    public function getLoadBalancerMock()
    {
        $loadBalancerMock = $this
            ->getMockBuilder('Saturne\Component\LoadBalancer\LoadBalancer')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $loadBalancerMock
            ->expects($this->any())
            ->method('affectClient')
            ->willReturn(true)
        ;
        return $loadBalancerMock;
    }
}
