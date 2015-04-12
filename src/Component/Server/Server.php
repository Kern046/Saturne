<?php

namespace Saturne\Component\Server;

use Saturne\Core\Kernel\EngineKernel;
use Saturne\Component\Event\EventManager;

/**
 * @name Server
 * @author Axel Venet <axel-venet@developtech.fr>
 */
class Server implements ServerInterface
{
    /** @var array<resource> **/
    private $inputs = [];
    /** @var array<resource> **/
    private $outputs = [];
    /** @var boolean **/
    private $listen = true;
    /**
     * {@inheritdoc}
     */
    public function listen()
    {
        $this->addInput('master', stream_socket_server('tcp://127.0.0.1:8889', $errno, $errstr));
        
        EngineKernel::getInstance()->getEventManager()->transmit(EventManager::NETWORK_SERVER_LISTENING, [
            'protocol' => 'tcp',
            'ip' => '127.0.0.1',
            'port' => 8889,
            'message' => 'The server is now listening at tcp://127.0.0.1:8889'
        ]);
        
        while($this->listen === true)
        {
            $inputs = $this->getMappedInputs();
            $outputs = $this->getMappedOutputs();
            $errors = null;
            if(($nbUpdatedStreams = stream_select($inputs, $outputs, $errors, 10)) !== false)
            {
                if($nbUpdatedStreams < 1)
                {
                    continue;
                }
                $this->treatInputs($inputs);
                $this->treatOutputs($outputs);
            }
        }
    }
    
    /**
     * {@inheritdoc}
     */
    public function treatInputs($inputs)
    {
        foreach($inputs as $input)
        {
            $name = array_search($input, $this->inputs);
            
            if($name === 'master')
            {
                $this->acceptConnection($input);
            }
        }
    }
    
    /**
     * {@inheritdoc}
     */
    public function treatOutputs($outputs)
    {
        foreach($outputs as $output)
        {
            $name = array_search($input, $this->outputs);
        }
    }
    
    /**
     * {@inheritdoc}
     */
    public function acceptConnection($input)
    {
        
    }
    
    /**
     * {@inheritdoc}
     */
    public function closeConnection()
    {
        
    }
    
    /**
     * {@inheritdoc}
     */
    public function addInput($name, $input)
    {
        $this->inputs[$name] = $input;
    }
    
    /**
     * {@inheritdoc}
     */
    public function hasInput($name)
    {
        return isset($this->inputs[$name]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function getInput($name)
    {
        return $this->inputs[$name];
    }
    
    /**
     * {@inheritdoc}
     */
    public function removeInput($name)
    {
        unset($this->inputs[$name]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function getInputs()
    {
        return $this->inputs;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getMappedInputs()
    {
        return array_map(function($input){ return $input; }, $this->inputs);
    }
    
    /**
     * {@inheritdoc}
     */
    public function addOutput($name, $output)
    {
        if($this->hasOutput($name))
        {
            throw new \InvalidArgumentException("The given output \"$name\" already exists");
        }
        $this->outputs[$name] = $output;
    }
    
    /**
     * {@inheritdoc}
     */
    public function hasOutput($name)
    {
        return isset($this->outputs[$name]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function getOutput($name)
    {
        if(!$this->hasOutput($name))
        {
            throw new \InvalidArgumentException("The requested output \"$name\" doesn't exist");
        }
        return $this->outputs[$name];
    }
    
    /**
     * {@inheritdoc}
     */
    public function removeOutput($name)
    {
        unset($this->outputs[$name]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function getOutputs()
    {
        return $this->outputs;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getMappedOutputs()
    {
        return array_map(function($output){ return $output; }, $this->outputs);
    }
}