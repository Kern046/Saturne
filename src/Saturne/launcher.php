<?php

$file = '"' . __DIR__ . '\\engine.php"';

$command = 
    (PHP_OS === 'WINNT')
    ? "start php $file"
    : "php $file &"        
;

pclose(popen($command, 'r'));