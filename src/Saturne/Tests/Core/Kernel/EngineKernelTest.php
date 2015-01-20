<?php
namespace Saturne\Tests\Core\Kernel;

use Saturne\Core\Kernel\EngineKernel;

class EngineKernelTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $engine = new EngineKernel();
        
        $this->assertInstanceOf('Saturne\Core\Kernel\EngineKernel', $engine);
    }
    
}