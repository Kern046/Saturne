<?php

$name = $options['thread'];
fputs(STDOUT, json_encode([
    'emmitter' => $name,
    'memory' => memory_get_usage(),
    'allocated-memory' => memory_get_usage(true)
]));