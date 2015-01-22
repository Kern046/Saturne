<?php

namespace Saturne\Tests\Component\Logger;

use Saturne\Component\Logger\FileLogger;

class FileLoggerTest extends \PHPUnit_Framework_TestCase
{
    /** @var FileLogger **/
    private $logger;
    
    public function setUp()
    {
        $this->logger = new FileLogger();
    }
    
    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidLog()
    {
        $this->logger->log([]);
    }
}