<?php

// The first index is the script name
array_shift($argv);

function getScriptOptions($args)
{
    $options = [];
    foreach($args as $arg)
    {
        $data = explode('=', $arg);
        $options[substr($data[0], 2)] = $data[1];
    }
    return $options;
}

function getTarget($options)
{
    return
        (isset($options['target']))
        ? $options['target'] . '.php'
        : 'engine.php'
    ;
}

$options = getScriptOptions($argv);
$target = getTarget($options);
$implodedOptions = implode(' ', $argv);

$config = [
    'WINNT' => [
        'command' => 'start php "' . __DIR__ . DIRECTORY_SEPARATOR . $target . '" ' . $implodedOptions
    ],
    'Linux' => [
        'command' => 'php "' . __DIR__ . DIRECTORY_SEPARATOR . $target . '" ' . $implodedOptions . ' &'
    ]
];

pclose(popen($config[PHP_OS]['command'], 'r'));