<?php

require(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php');

$process = new Saturne\Core\Kernel\ProcessKernel();
$process->init($options);
$process->run();