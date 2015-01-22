<?php

namespace Saturne\Tests\Component\Event;

use Saturne\Component\Event\EventManager;

class EventManagerTest extends \PHPUnit_Framework_TestCase
{
    /** @var EventManager **/
    private $eventManager;
    
    public function setUp()
    {
        $this->eventManager = new EventManager();
    }
    
    public function testFunctional()
    {
        $mock = $this->getMock('Saturne\Component\Event\EventListenerInterface');
        
        $mock
            ->expects($this->once())
            ->method('receiveEvent')
            ->will($this->returnValue(true))
        ;
        
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
}