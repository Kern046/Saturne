<?php

namespace Saturne\Tests\Model;

use Saturne\Model\Process;

use Saturne\Model\Client;

class ProcessTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        // Fake resources
        $input = fopen('php://temp', 'r');
        $output = fopen('php://temp', 'w');
        $client = new Client();
        
        $process =
            (new Process())
            ->setName('test-process')
            ->setInput($input)
            ->setOutput($output)
            ->setMemory(95885)
            ->setAllocatedMemory(122354)
            ->addClient(new Client())
            ->addClient($client)
            ->removeClient($client)
        ;
        
        $this->assertEquals('test-process', $process->getName());
        $this->assertInternalType('resource', $process->getInput());
        $this->assertInternalType('resource', $process->getOutput());
        $this->assertEquals(95885, $process->getMemory());
        $this->assertEquals(122354, $process->getAllocatedMemory());
        $this->assertInstanceOf('DateTime', $process->getStartTime());
        $this->assertCount(1, $process->getClients());
    }
}