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
        : 'master.php'
    ;
}

$options = getScriptOptions($argv);
$target = getTarget($options);
$implodedOptions = implode(' ', $argv);

require(__DIR__ . DIRECTORY_SEPARATOR . $target);