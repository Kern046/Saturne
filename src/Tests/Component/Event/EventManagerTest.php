<?php

namespace Saturne\Tests\Component\Event;

use Saturne\Component\Event\EventManager;
use Saturne\Tests\Component\Event\Mock\EventListenerMock;

class EventManagerTest extends \PHPUnit_Framework_TestCase
{
    /** @var EventManager **/
    private $eventManager;
    
    public function setUp()
    {
        $this->eventManager = new EventManager($this->getEngineMock());
    }
    
    public function testFunctional()
    {
        $mock = new EventListenerMock();
        
        $this->eventManager->addListener($mock);
        
        $this->eventManager->transmit('test-event', []);
        
        $this->eventManager->removeListener($mock);
    }
    
    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidAddListener()
    {
        $this->eventManager->addListener(new \stdClass());
    }
    
    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidRemoveListener()
    {
        $this->eventManager->removeListener(new \stdClass());
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