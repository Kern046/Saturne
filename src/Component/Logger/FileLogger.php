<?php

namespace Saturne\Component\Logger;

use Saturne\Component\Event\EventListenerTrait;
use Saturne\Component\Event\EventManager;

/**
 * @name FileLogger
 * @author Axel Venet <axel-venet@developtech.fr>
 */
class FileLogger implements LoggerInterface
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
        EventManager::CLIENT_AFFECTION => 'log'
    ];
    /** @var string **/
    private $dir;
    
    public function __construct()
    {
        $this->dir = dirname(dirname(dirname(__DIR__))) . DIRECTORY_SEPARATOR . 'logs';
        if(!is_dir($this->dir))
        {
            mkdir($this->dir);
        }
    }
    
    /**
     * {@inheritdoc}
     */
    public function log($data)
    {
        if(!isset($data['message']))
        {
            throw new \InvalidArgumentException('The data must have a message');
        }
        $file = $this->dir . DIRECTORY_SEPARATOR . date('d_m_Y') . '.log';
        file_put_contents($file, date('[H:i:s] ') . $data['message'] . PHP_EOL, FILE_APPEND);
    }
}