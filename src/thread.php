<?php

fputs(STDOUT, json_encode([
    'memory' => memory_get_usage(),
    'allocated-memory' => memory_get_usage(true)
]));
