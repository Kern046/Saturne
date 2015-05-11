<?php

namespace Saturne\Tests\Component\Thread;

use Saturne\Component\Thread\ThreadGateway;

use Saturne\Model\Thread;

class ThreadGatewayTest extends \PHPUnit_Framework_TestCase
{
    /** @var ThreadGateway **/
    private $gateway;
    /** @var Thread **/
    private $thread;
    
    public function setUp()
    {
        $this->gateway = new ThreadGateway($this->getEngineMock());
        
        $handle = fopen('php://temp', 'r+');
        
        $this->thread =
            (new Thread())
            ->setName('Thread_Test')
            ->setInput($handle)
            ->setOutput($handle)
        ;
    }
    
    public function testWriteTo()
    {
        $this->gateway->writeTo($this->thread, ['command' => 'test']);
    }
    
    public function testRead()
    {
        $this->gateway->writeTo($this->thread, ['command' => 'test']);
        
        $output = $this->gateway->read($this->thread);
        
        $this->assertArrayHasKey('command', $output);
        $this->assertEquals('test', $output['command']);
        
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