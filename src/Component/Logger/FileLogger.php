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
        EventManager::NETWORK_PROCESS_LISTENING => 'log',
        EventManager::NETWORK_PROCESS_SHUTDOWN => 'log',
        EventManager::NETWORK_PROCESSES_CLEARED => 'log',
        EventManager::NETWORK_NEW_PROCESS => 'log',
        EventManager::CLIENT_AFFECTION => 'log'
    ];
    /** @var string **/
    private $dir;
    /** @var string **/
    private $file;
    /** @var EngineKernel **/
    private $engine;
    
    public function __construct($engine)
    {
        $this->engine = $engine;
        $this->dir = dirname(dirname(dirname(__DIR__))) . DIRECTORY_SEPARATOR . 'logs';
        if(!is_dir($this->dir))
        {
            mkdir($this->dir);
        }
        $this->setFile(date('d_m_Y'));
    }
    
    public function setFile($filename)
    {
        $this->file = $this->dir . DIRECTORY_SEPARATOR . $filename . '.log';
    }
    
    /**
     * {@inheritdoc}
     */
    public function log($data)
    {
        if(empty($data['message']))
        {
            throw new \InvalidArgumentException('The data must have a message');
        }
        
        $data['message'] =
            (isset($data['emitter']))
            ? $data['emitter'] . ': ' . $data['message']
            : $data['message']
        ;
        
        file_put_contents($this->file, date('[H:i:s] ') . $data['message'] . PHP_EOL, FILE_APPEND);
    }
}