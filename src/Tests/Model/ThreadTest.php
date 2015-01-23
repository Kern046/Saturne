<?php

namespace Saturne\Tests\Model;

use Saturne\Model\Thread;

class ThreadTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        // Fake resources
        $input = opendir('src');
        $output = opendir('src');
        
        $thread =
            (new Thread('test-thread', $input, $output))
            ->setMemory(95885)
            ->setAllocatedMemory(122354)
        ;
        
        $this->assertEquals('test-thread', $thread->getName());
        $this->assertInternalType('resource', $thread->getInput());
        $this->assertInternalType('resource', $thread->getOutput());
        $this->assertEquals(95885, $thread->getMemory());
        $this->assertEquals(122354, $thread->getAllocatedMemory());
        $this->assertInternalType('integer', $thread->getStartTime());
        
        closedir($input);
        closedir($output);
    }
}