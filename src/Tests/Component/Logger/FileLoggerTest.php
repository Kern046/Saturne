<?php

namespace Saturne\Tests\Component\Logger;

use Saturne\Component\Logger\FileLogger;

class FileLoggerTest extends \PHPUnit_Framework_TestCase
{
    /** @var FileLogger **/
    private $logger;
    
    public function setUp()
    {
        $this->logger = new FileLogger($this->getEngineMock());
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