<?php

$name = $options['process'];
fputs(STDOUT, json_encode([
    'emmitter' => $name,
    'memory' => memory_get_usage(),
    'allocated-memory' => memory_get_usage(true)
]));
sleep(rand(2,10));