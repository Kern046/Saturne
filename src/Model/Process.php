<?php

namespace Saturne\Model;

use Saturne\Model\Client;

/**
 * @name Process
 * @author Axel Venet <axel-venet@developtech.fr>
 */
class Process
{
    /** @var string **/
    private $name;
    /** @var integer **/
    private $memory;
    /** @var integer **/
    private $allocatedMemory;
    /** @var \DateTime **/
    private $startTime;
    /** @var resource **/
    private $input;
    /** @var resource **/
    private $output;
    /** @var resource **/
    private $process;
    /** @var array **/
    private $clients = [];
    /** @var string **/
    private $address;
    
    public function __construct()
    {
        $this->startTime = new \DateTime();
    }
    
    public function refreshMemory()
    {
        $this->memory = memory_get_usage();
        $this->allocatedMemory = memory_get_usage(true);
    }
    
    /**
     * @param integer $memory
     * @return Process
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
     * @return Process
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
     * Set the Process input
     * 
     * @param resource $input
     * @return Process
     */
    public function setInput($input)
    {
        $this->input = $input;
        
        return $this;
    }
    
    /**
     * @return resource
     */
    public function getInput()
    {
        return $this->input;
    }
    
    /**
     * Set the Process output
     * 
     * @param resource $output
     * @return Process
     */
    public function setOutput($output)
    {
        $this->output = $output;
        
        return $this;
    }
    
    /**
     * @return resource
     */
    public function getOutput()
    {
        return $this->output;
    }
    
    /**
     * Set name
     * 
     * @param string $name
     * @return Process
     */
    public function setName($name)
    {
        $this->name = $name;
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * Set process
     * 
     * @param resource $process
     * @return Process
     */
    public function setProcess($process)
    {
        $this->process = $process;
        
        return $this;
    }
    
    /**
     * @return resource
     */
    public function getProcess()
    {
        return $this->process;
    }
    
    /**
     * Hash a client and add it to the thread clients
     * 
     * @param Client $client
     * @return Process
     */
    public function addClient(Client $client)
    {
        if(!$this->hasClient($client))
        {
            $this->clients[spl_object_hash($client)] = $client;
        }
        return $this;
    }
    
    /**
     * Remove a client if it is stored in the current thread
     * 
     * @param Client $client
     * @return Process
     */
    public function removeClient(Client $client)
    {
        if($this->hasClient($client))
        {
            unset($this->clients[spl_object_hash($client)]);
        }
        return $this;
    }
    
    /**
     * Check if a client is already registered in the current clients
     * 
     * @param Client $client
     * @return boolean
     */
    public function hasClient(Client $client)
    {
        return isset($this->clients[spl_object_hash($client)]);
    }
    
    /**
     * Return all the current clients
     * 
     * @return array<Client>
     */
    public function getClients()
    {
        return $this->clients;
    }
    
    public function setAddress($address)
    {
        $this->address = $address;
        
        return $this;
    }
    
    public function getAddress()
    {
        return $this->address;
    }
}