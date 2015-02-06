<?php

namespace Saturne\Tests\Component\Client;

use Saturne\Component\Client\ClientManager;

use Saturne\Core\Kernel\EngineKernel;

class ClientManagerTest extends \PHPUnit_Framework_TestCase
{
    /** @var ClientManager **/
    private $clientManager;
    
    public function setUp()
    {
        $engine = EngineKernel::getInstance();
        $engine->init();
        $this->clientManager = $engine->getClientManager();
    }
    
    public function testCreateConnection()
    {
        $result = $this->clientManager->createConnection([]);
    }
    
    public function testGetNetworkData()
    {
        $this->markTestIncomplete('Need to implement network operations');
    }
}
