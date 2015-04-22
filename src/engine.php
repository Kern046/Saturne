<?php

require(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php');

$engine = Saturne\Core\Kernel\EngineKernel::getInstance();
$engine->init();