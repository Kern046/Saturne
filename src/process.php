<?php

require(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php');

$process = new Saturne\Core\Kernel\ProcessKernel();
$process->init();
$process->run($options);
/**
$name = $options['process'];
fputs(STDOUT, json_encode([
    'emmitter' => $name,
    'memory' => memory_get_usage(),
    'allocated-memory' => memory_get_usage(true)
]));
sleep(rand(2,10));
 *
 */