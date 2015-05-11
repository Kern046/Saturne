<?php
namespace Saturne\Tests\Core\Kernel;

use Saturne\Core\Kernel\EngineKernel;

class EngineKernelTest extends \PHPUnit_Framework_TestCase
{
    /** @var EngineKernel **/
    private $engine;
    
    public function setUp()
    {
        $this->engine = new EngineKernel();
        $this->engine->init();
        
        $container = $this->engine->getContainer();
        
        $container->set('saturne.memory_manager', $this->getMemoryManagerMock());
        $container->set('saturne.event_manager', $this->getEventManagerMock());
        $container->set('saturne.logger.cli', $this->getCliLoggerMock());
        $container->set('saturne.logger.file', $this->getFileLoggerMock());
        $container->set('saturne.thread_manager', $this->getThreadManagerMock());
        $container->set('saturne.load_balancer', $this->getLoadBalancerMock());
        $container->set('saturne.client_manager', $this->getClientManagerMock());
        $container->set('saturne.server', $this->getServerMock());
    }
    
    public function testInit()
    {
        $this->assertInstanceOf('Saturne\Component\Client\ClientManager', $this->engine->get('saturne.client_manager'));
        $this->assertInstanceOf('Saturne\Component\Event\EventManager', $this->engine->get('saturne.event_manager'));
        $this->assertInstanceOf('Saturne\Component\Logger\CliLogger', $this->engine->get('saturne.logger.cli'));
        $this->assertInstanceOf('Saturne\Component\Logger\FileLogger', $this->engine->get('saturne.logger.file'));
        $this->assertInstanceOf('Saturne\Component\Thread\ThreadManager', $this->engine->get('saturne.thread_manager'));
        $this->assertInstanceOf('Saturne\Component\LoadBalancer\LoadBalancer', $this->engine->get('saturne.load_balancer'));
        $this->assertInstanceOf('Saturne\Component\Memory\MemoryManager', $this->engine->get('saturne.memory_manager'));
        $this->assertInstanceOf('Saturne\Component\Server\Server', $this->engine->get('saturne.server'));
    }
    
    public function testRun()
    {
        
    }
    
    public function getMemoryManagerMock()
    {
        $managerMock = $this
            ->getMockBuilder('Saturne\Component\Memory\MemoryManager')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        return $managerMock;
    }
    
    public function getEventManagerMock()
    {
        $managerMock = $this
            ->getMockBuilder('Saturne\Component\Event\EventManager')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $managerMock
            ->expects($this->any())
            ->method('addListener')
            ->willReturn(true)
        ;
        $managerMock
            ->expects($this->any())
            ->method('transmit')
            ->willReturn(true)
        ;
        return $managerMock;
    }
    
    public function getCliLoggerMock()
    {
        $managerMock = $this
            ->getMockBuilder('Saturne\Component\Logger\CliLogger')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $managerMock
            ->expects($this->any())
            ->method('log')
            ->willReturn(true)
        ;
        return $managerMock;
    }
    
    public function getFileLoggerMock()
    {
        $managerMock = $this
            ->getMockBuilder('Saturne\Component\Logger\FileLogger')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $managerMock
            ->expects($this->any())
            ->method('log')
            ->willReturn(true)
        ;
        return $managerMock;
    }
    
    public function getThreadManagerMock()
    {
        $managerMock = $this
            ->getMockBuilder('Saturne\Component\Thread\ThreadManager')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $managerMock
            ->expects($this->any())
            ->method('launchThreads')
            ->willReturn(true)
        ;
        return $managerMock;
    }
    
    public function getLoadBalancerMock()
    {
        $managerMock = $this
            ->getMockBuilder('Saturne\Component\LoadBalancer\LoadBalancer')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        return $managerMock;
    }
    
    public function getClientManagerMock()
    {
        $managerMock = $this
            ->getMockBuilder('Saturne\Component\Client\ClientManager')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        return $managerMock;
    }
    
    public function getServerMock()
    {
        $managerMock = $this
            ->getMockBuilder('Saturne\Component\Server\Server')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $managerMock
            ->expects($this->any())
            ->method('listen')
            ->willReturn(true)
        ;
        return $managerMock;
    }
    
}