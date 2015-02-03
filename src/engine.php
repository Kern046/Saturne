<?php

require(getcwd() . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php');

$engine = Saturne\Core\Kernel\EngineKernel::getInstance();
$engine->init();