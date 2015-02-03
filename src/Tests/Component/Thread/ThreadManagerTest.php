<?php

namespace Saturne\Tests\Component\Thread;

use Saturne\Component\Thread\ThreadManager;

class ThreadManagerTest extends \PHPUnit_Framework_TestCase
{
    /** @var ThreadManager **/
    private $manager;
    
    public function setUp()
    {
        $this->manager = new ThreadManager();
        
        $this->manager->addThread();
        $this->manager->addThread();
        $this->manager->addThread();
    }
    
    public function testGetThreads()
    {
        $threads = $this->manager->getThreads();
        
        $this->assertCount(3, $threads);
        $this->assertEquals('Thread_2', $threads['Thread_2']->getName());
    }
    
    public function testAddThread()
    {
        $this->manager->addThread();
        $this->manager->addThread();
        
        $threads = $this->manager->getThreads();
        
        $this->assertCount(5, $threads);
        $this->assertArrayHasKey('Thread_3', $threads);
        $this->assertInstanceOf('Saturne\Model\Thread', $threads['Thread_4']);
    }
    
    public function testRemoveThread()
    {
        $this->manager->removeThread('Thread_1');
        
        $threads = $this->manager->getThreads();
        
        $this->assertCount(2, $threads);
        $this->assertArrayNotHasKey('Thread_1', $threads);
    }
    
    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidRemoveThread()
    {
        $this->manager->removeThread('Thread_10');
    }
    
    public function testShutdownThread()
    {
        $this->markTestIncomplete('Wait for shutdown functionality');
    }
}