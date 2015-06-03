<?php

namespace Saturne\Component\Server;

use Saturne\Core\Kernel\MasterKernel;

abstract class AbstractServer implements ServerInterface
{
    /** @var array<resource> **/
    protected $inputs = [];
    /** @var array<resource> **/
    protected $outputs = [];
    /** @var boolean **/
    protected $listen = true;
    /** @var MasterKernel **/
    protected $engine;
    
    public function __construct($engine)
    {
        $this->engine = $engine;
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