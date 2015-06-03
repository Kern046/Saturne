<?php

namespace Saturne\Tests\Component\Process;

use Saturne\Component\Process\MasterManager;

class ProcessManagerTest extends \PHPUnit_Framework_TestCase
{
    /** @var MasterManager **/
    private $manager;
    
    public function setUp()
    {
        $this->manager = new MasterManager($this->getEngineMock());
    }
    
    public function testGetProcesses()
    {
        $this->manager->initProcess();
        $this->manager->initProcess();
        $this->manager->initProcess();
        $processes = $this->manager->getProcesses();
        
        $this->assertCount(3, $processes);
        $this->assertEquals('Process_2', $processes['Process_2']->getName());
        unset($this->manager);
    }
    
    public function testAddProcess()
    {
        $this->manager->initProcess();
        $this->manager->initProcess();
        $this->manager->initProcess();
        $this->manager->initProcess();
        $this->manager->initProcess();
        
        $processes = $this->manager->getProcesses();
        
        $this->assertCount(5, $processes);
        $this->assertArrayHasKey('Process_3', $processes);
        $this->assertInstanceOf('Saturne\Model\Process', $processes['Process_4']);
        unset($this->manager);
    }
    
    public function testRemoveProcess()
    {
        $this->manager->initProcess();
        $this->manager->initProcess();
        $this->manager->initProcess();
        
        $this->manager->removeProcess('Process_1', 'Process_1 has finished tests');
        
        $processes = $this->manager->getProcesses();
        
        $this->assertCount(2, $processes);
        $this->assertArrayNotHasKey('Process_1', $processes);
        unset($this->manager);
    }
    
    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidRemoveProcess()
    {
        $this->manager->removeProcess('Process_10', 'Process_10 has finished tests');
    }
    
    public function testShutdownProcess()
    {
        $this->markTestIncomplete('Wait for shutdown functionality');
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
            ->method('get')
            ->with($this->logicalOr(
                $this->equalTo('saturne.server'),
                $this->equalTo('saturne.process_gateway'),
                $this->equalTo('saturne.memory_manager')
            ))
            ->willReturnCallback([$this, 'getComponentMock'])
        ;
        $engineMock
            ->expects($this->any())
            ->method('getContainer')
            ->willReturnCallback([$this, 'getContainerMock'])
        ;
        return $engineMock;
    }
    
    public function getComponentMock($component)
    {
        $components = [
            'saturne.server' => 'getServerMock',
            'saturne.process_gateway' => 'getProcessGatewayMock',
            'saturne.memory_manager' => 'getMemoryManagerMock'
        ];
        return $this->{$components[$component]}();
    }
    
    public function getServerMock()
    {
        $serverMock = $this
            ->getMockBuilder('Saturne\Component\Server\Server')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $serverMock
            ->expects($this->any())
            ->method('removeInput')
            ->willReturn(true)
        ;
        $serverMock
            ->expects($this->any())
            ->method('removeOutput')
            ->willReturn(true)
        ;
        $serverMock
            ->expects($this->any())
            ->method('addInput')
            ->willReturn(true)
        ;
        $serverMock
            ->expects($this->any())
            ->method('addOutput')
            ->willReturn(true)
        ;
        return $serverMock;
    }
    
    public function getProcessGatewayMock()
    {
        $processGatewayMock = $this
            ->getMockBuilder('Saturne\Component\Process\ProcessGateway')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $processGatewayMock
            ->expects($this->any())
            ->method('writeTo')
            ->willReturn(true)
        ;
        return $processGatewayMock;
    }
    
    public function getMemoryManagerMock()
    {
        $memoryManagerMock = $this
            ->getMockBuilder('Saturne\Component\Memory\MemoryManager')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $memoryManagerMock
            ->expects($this->any())
            ->method('refreshMemory')
            ->willReturn(true)
        ;
        $memoryManagerMock
            ->expects($this->any())
            ->method('getAllocatedMemory')
            ->willReturn(500000)
        ;
        return $memoryManagerMock;
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
}