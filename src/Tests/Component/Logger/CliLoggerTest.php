<?php

namespace Saturne\Tests\Logger;

use Saturne\Component\Logger\CliLogger;

class CliLoggerTest extends \PHPUnit_Framework_TestCase
{
    /** @var CliLogger **/
    private $logger;
    
    public function setUp()
    {
        $this->logger = new CliLogger($this->getEngineMock());
    }
    
    public function testLog()
    {
        ob_start();
        $this->logger->log(['message' => 'test ']);
        
        $this->assertContains('test', explode(' ', ob_get_contents()));
        ob_end_clean();
    }
    
    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidLog()
    {
        $this->logger->log([]);
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