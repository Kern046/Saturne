<?php

namespace Saturne\Tests\Component\Process;

use Saturne\Component\Process\ProcessGateway;

use Saturne\Model\Process;

class ProcessGatewayTest extends \PHPUnit_Framework_TestCase
{
    /** @var ProcessGateway **/
    private $gateway;
    /** @var Process **/
    private $process;
    
    public function setUp()
    {
        $this->gateway = new ProcessGateway($this->getEngineMock());
        
        $handle = fopen('php://temp', 'r+');
        
        $this->process =
            (new Process())
            ->setName('Process_Test')
            ->setInput($handle)
            ->setOutput($handle)
        ;
    }
    
    public function testWriteTo()
    {
        $this->gateway->writeTo($this->process, ['command' => 'test']);
    }
    
    public function testRead()
    {
        $this->gateway->writeTo($this->process, ['command' => 'test']);
        
        $output = $this->gateway->read($this->process);
        
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