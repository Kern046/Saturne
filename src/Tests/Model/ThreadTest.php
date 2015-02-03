<?php

namespace Saturne\Tests\Model;

use Saturne\Model\Thread;

use Saturne\Model\Client;

class ThreadTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        // Fake resources
        $input = fopen('php://temp', 'r');
        $output = fopen('php://temp', 'w');
        $client = new Client();
        
        $thread =
            (new Thread())
            ->setName('test-thread')
            ->setInput($input)
            ->setOutput($output)
            ->setMemory(95885)
            ->setAllocatedMemory(122354)
            ->addClient(new Client())
            ->addClient($client)
            ->removeClient($client)
        ;
        
        $this->assertEquals('test-thread', $thread->getName());
        $this->assertInternalType('resource', $thread->getInput());
        $this->assertInternalType('resource', $thread->getOutput());
        $this->assertEquals(95885, $thread->getMemory());
        $this->assertEquals(122354, $thread->getAllocatedMemory());
        $this->assertInstanceOf('DateTime', $thread->getStartTime());
        $this->assertCount(1, $thread->getClients());
    }
}