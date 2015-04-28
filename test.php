<?php

$socket = stream_socket_client('tcp://127.0.0.1:8889');

stream_socket_sendto($socket, json_encode([
    'action' => 'client:connect',
    'user-agent' => "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.90 Safari/537.36"
]));

fclose($socket);