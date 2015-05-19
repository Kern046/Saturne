<?php

require(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php');

$engine = new Saturne\Core\Kernel\MasterKernel();
$engine->init();
$engine->run();