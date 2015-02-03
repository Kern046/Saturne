<?php

namespace Saturne\Model;

use Saturne\Model\Client;

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
    /** @var array **/
    private $clients;
    
    public function __construct()
    {
        $this->startTime = new \DateTime();
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
     * Set the Thread input
     * 
     * @param resource $input
     * @return Thread
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
     * Set the Thread output
     * 
     * @param resource $output
     * @return Thread
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
     * @return Thread
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
     * Hash a client and add it to the thread clients
     * 
     * @param Client $client
     * @return Thread
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
     * @return Thread
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
}