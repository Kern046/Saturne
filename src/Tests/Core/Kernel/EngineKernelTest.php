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
    }
    
    public function testGetManager()
    {
        $eventManager = $this->engine->getEventManager();
        
        $this->assertInstanceOf('Saturne\Component\Event\EventManager', $eventManager);
    }
    
    public function testThrowEvent()
    {
        $eventManager = $this->engine->getEventManager();
        
        $mock = $this->getMock('Saturne\Component\Event\EventListenerInterface');
        
        $mock
            ->expects($this->once())
            ->method('receiveEvent')
            ->will($this->returnCallback([$this, 'eventCallback']))
        ;
        
        $eventManager->addListener($mock);
        
        $this->engine->throwEvent('test-event');
    }
    
    public function eventCallback()
    {
        $this->assertTrue(true);
    }
}