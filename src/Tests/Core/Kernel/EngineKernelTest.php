<?php
namespace Saturne\Tests\Core\Kernel;

use Saturne\Core\Kernel\EngineKernel;

use Saturne\Tests\Component\Event\Mock\EventListenerMock;

class EngineKernelTest extends \PHPUnit_Framework_TestCase
{
    /** @var EngineKernel **/
    private $engine;
    
    public function setUp()
    {
        $this->engine = EngineKernel::getInstance();
        $this->engine->init();
        $this->engine->setClientManager();
        $this->engine->setEventManager();
        $this->engine->setLoadBalancer();
        $this->engine->setLoggers();
        $this->engine->setMemoryManager();
        $this->engine->setServer();
        $this->engine->setThreadManager();
    }
    
    public function testGetEventManager()
    {
        $eventManager = $this->engine->getEventManager();
        
        $this->assertInstanceOf('Saturne\Component\Event\EventManager', $eventManager);
    }
    
    public function testGetThreadManager()
    {
        $threadManager = $this->engine->getThreadManager();
        
        $this->assertInstanceOf('Saturne\Component\Thread\ThreadManager', $threadManager);
    }
    
    public function testThrowEvent()
    {
        $eventManager = $this->engine->getEventManager();
        
        $mock = new EventListenerMock();
        
        $eventManager->addListener($mock);
        
        $this->engine->throwEvent('test-event');
    }
    
    public function eventCallback()
    {
        $this->assertTrue(true);
    }
}