<?php

namespace Saturne\Tests\Component\Thread;

use Saturne\Component\Thread\ThreadManager;

class ThreadManagerTest extends \PHPUnit_Framework_TestCase
{
    /** @var ThreadManager **/
    private $manager;
    
    public function setUp()
    {
        $this->manager = new ThreadManager($this->getEngineMock());
    }
    
    public function testGetThreads()
    {
        $this->manager->initThread();
        $this->manager->initThread();
        $this->manager->initThread();
        $threads = $this->manager->getThreads();
        
        $this->assertCount(3, $threads);
        $this->assertEquals('Thread_2', $threads['Thread_2']->getName());
        unset($this->manager);
    }
    
    public function testAddThread()
    {
        $this->manager->initThread();
        $this->manager->initThread();
        $this->manager->initThread();
        $this->manager->initThread();
        $this->manager->initThread();
        
        $threads = $this->manager->getThreads();
        
        $this->assertCount(5, $threads);
        $this->assertArrayHasKey('Thread_3', $threads);
        $this->assertInstanceOf('Saturne\Model\Thread', $threads['Thread_4']);
        unset($this->manager);
    }
    
    public function testRemoveThread()
    {
        $this->manager->initThread();
        $this->manager->initThread();
        $this->manager->initThread();
        
        $this->manager->removeThread('Thread_1', 'Thread_1 has finished tests');
        
        $threads = $this->manager->getThreads();
        
        $this->assertCount(2, $threads);
        $this->assertArrayNotHasKey('Thread_1', $threads);
        unset($this->manager);
    }
    
    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidRemoveThread()
    {
        $this->manager->removeThread('Thread_10', 'Thread_10 has finished tests');
    }
    
    public function testShutdownThread()
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
                $this->equalTo('saturne.thread_gateway'),
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
            'saturne.thread_gateway' => 'getThreadGatewayMock',
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
    
    public function getThreadGatewayMock()
    {
        $threadGatewayMock = $this
            ->getMockBuilder('Saturne\Component\Thread\ThreadGateway')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $threadGatewayMock
            ->expects($this->any())
            ->method('writeTo')
            ->willReturn(true)
        ;
        return $threadGatewayMock;
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