<?php

namespace Saturne\Component\Server;

/**
 * @name Server
 * @author Axel Venet <axel-venet@developtech.fr>
 */
class Server implements ServerInterface
{
    /** @var array<resource> **/
    private $inputs;
    /** @var array<resource> **/
    private $outputs;
    /** @var boolean **/
    private $listen = true;
    /**
     * {@inheritdoc}
     */
    public function listen()
    {
        while($this->listen === true)
        {
            $inputs = $this->getMappedInputs();
            $outputs = $this->getMappedOutputs();
            if(($nbUpdatedStreams = stream_select($inputs, $outputs, $errors = null, 10)) !== false)
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
    
    public function treatInputs()
    {
        
    }
    
    public function treatOutputs()
    {
        
    }
    
    /**
     * {@inheritdoc}
     */
    public function acceptConnection()
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
        return array_map(function($name, $input){ return $input; }, $this->inputs);
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
        return array_map(function($name, $output){ return $output; }, $this->outputs);
    }
}