<?php

namespace Saturne\Tests\Component\Memory;

use Saturne\Component\Memory\MemoryManager;

class MemoryManagerTest extends \PHPUnit_Framework_TestCase
{
    /** @var MemoryManager **/
    private $manager;
    
    public function setUp()
    {
        $this->manager = new MemoryManager();
    }
    
    public function testRefreshMemory()
    {
        $this->manager->refreshMemory();
        
        $memory = $this->manager->getMemory();
        $allocatedMemory = $this->manager->getAllocatedMemory();
        
        $this->assertGreaterThan(0, $memory);
        $this->assertInternalType('integer', $memory);
        $this->assertGreaterThan(0, $allocatedMemory);
        $this->assertInternalType('integer', $allocatedMemory);
    }
}