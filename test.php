<?php

$socket = stream_socket_client('tcp://127.0.0.1:8889');

stream_socket_sendto($socket, json_encode([
    'action' => 'client:connect',
    'user-agent' => $_SERVER['HTTP_USER_AGENT']
]));

fclose($socket);