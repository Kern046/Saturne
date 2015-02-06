<?php

namespace Saturne\Tests\Model;

use Saturne\Model\Client;

class ClientTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $client =
            (new Client())
            ->setIp('127.0.0.1')
            ->setPort(4000)
            ->setUserAgent('Mozilla 5.0 Firefox')
        ;
        
        $this->assertEquals('127.0.0.1', $client->getIp());
        $this->assertEquals(4000, $client->getPort());
        $this->assertEquals('Mozilla 5.0 Firefox', $client->getUserAgent());
        $this->assertInstanceOf('DateTime', $client->getConnectionStart());
    }
}