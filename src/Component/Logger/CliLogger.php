<?php

namespace Saturne\Component\Logger;

use Saturne\Component\Event\EventListenerInterface;
use Saturne\Component\Event\EventManager;

/**
 * @name CliLogger
 * @author Axel Venet <axel-venet@developtech.fr>
 */
class CliLogger implements LoggerInterface, EventListenerInterface
{
    /** @var array **/
    private $events = [
        EventManager::ENGINE_INITIALIZED => 'log'
    ];
    
    /**
     * {@inheritdoc}
     */
    public function supportsEvent($event)
    {
        return isset($this->events[$event]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function receiveEvent($event, $data)
    {
        if(!$this->supportsEvent($event))
        {
            return false;
        }
        $this->{$this->events[$event]}($data);
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
        echo(date('[H:i:s] ') . $data['message'] . PHP_EOL);
    }
}