<?php

namespace Saturne\Component\Logger;

use Saturne\Component\Event\EventListenerTrait;
use Saturne\Component\Event\EventManager;

/**
 * @name CliLogger
 * @author Axel Venet <axel-venet@developtech.fr>
 */
class CliLogger implements LoggerInterface
{
    use EventListenerTrait;
    /** @var array **/
    private $events = [
        EventManager::ENGINE_INITIALIZED => 'log',
        EventManager::ENGINE_SHUTDOWN => 'log',
        EventManager::NETWORK_SERVER_LISTENING => 'log',
        EventManager::NETWORK_NEW_CONNECTION => 'log',
        EventManager::NETWORK_SHUTDOWN => 'log',
        EventManager::NETWORK_THREADS_CLEARED => 'log',
        EventManager::CLIENT_AFFECTION => 'log',
    ];
    
    /**
     * {@inheritdoc}
     */
    public function log($data)
    {
        if(!isset($data['message']))
        {
            throw new \InvalidArgumentException('The data must have a message');
        }
        echo(date('[H:i:s] ') . $data['message'] . PHP_EOL);
    }
}