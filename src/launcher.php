<?php

$config = [
    'WINNT' => [
        'command' => 'start php "' . __DIR__ . DIRECTORY_SEPARATOR . 'engine.php"'
    ],
    'Linux' => [
        'command' => 'php "' . __DIR__ . DIRECTORY_SEPARATOR . 'engine.php" &'
    ]
];

pclose(popen($config[PHP_OS]['command'], 'r'));