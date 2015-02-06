<?php

namespace Saturne\Tests\Component\Client;

use Saturne\Core\Kernel\EngineKernel;

use Saturne\Component\Client\ClientListener;

class ClientListenerTest extends \PHPUnit_Framework_TestCase
{
    /** @var ClientListener **/
    private $listener;
    
    public function setUp()
    {
        $engine = EngineKernel::getInstance();
        $engine->init();
        $this->listener = new ClientListener();
    }
    
    public function testNewConnection()
    {
        $this->listener->newConnection([]);
    }
}