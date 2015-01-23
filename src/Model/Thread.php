<?php

namespace Saturne\Model;

/**
 * @name Thread
 * @author Axel Venet <axel-venet@developtech.fr>
 */
class Thread
{
    /** @var string **/
    private $name;
    /** @var integer **/
    private $memory;
    /** @var integer **/
    private $allocatedMemory;
    /** @var integer **/
    private $startTime;
    /** @var resource **/
    private $input;
    /** @var resource **/
    private $output;
    
    public function __construct($name, $input, $output)
    {
        $this->name = $name;
        $this->input = $input;
        $this->output = $output;
        $this->startTime = time();
    }
    
    /**
     * @param integer $memory
     * @return Thread
     */
    public function setMemory($memory)
    {
        $this->memory = $memory;
        
        return $this;
    }
    
    /**
     * @return integer
     */
    public function getMemory()
    {
        return $this->memory;
    }
    
    /**
     * @param integer $allocatedMemory
     * @return Thread
     */
    public function setAllocatedMemory($allocatedMemory)
    {
        $this->allocatedMemory = $allocatedMemory;
        
        return $this;
    }
    
    /**
     * @return integer
     */
    public function getAllocatedMemory()
    {
        return $this->allocatedMemory;
    }
    
    /**
     * @return integer
     */
    public function getStartTime()
    {
        return $this->startTime;
    }
    
    /**
     * @return resource
     */
    public function getInput()
    {
        return $this->input;
    }
    
    /**
     * @return resource
     */
    public function getOutput()
    {
        return $this->output;
    }
    
    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}