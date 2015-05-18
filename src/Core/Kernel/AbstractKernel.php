<?php

namespace Saturne\Core\Kernel;

use Saturne\Core\Container\KernelContainer;

abstract class AbstractKernel implements KernelInterface
{
    /** @var KernelContainer **/
    protected $container;
    
    /**
     * {@inheritdoc}
     */
    public function setContainer()
    {
        $this->container = new KernelContainer();
    }
    
    /**
     * {@inheritdoc}
     */
    public function getContainer()
    {
        return $this->container;
    }
    
    /**
     * {@inheritdoc}
     */
    public function get($name)
    {
        return $this->container->get($name);
    }
    
    /**
     * {@inheritdoc}
     */
    public function throwEvent($event, $data = [])
    {
        $this->get('saturne.event_manager')->transmit($event, $data);
    }
}