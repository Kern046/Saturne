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
    
    /**
     * Hash a client and add it to the thread clients
     * 
     * @param Client $client
     */
    public function addClient(Client $client)
    {
        if(!$this->hasClient($client))
        {
            $this->clients[spl_object_hash($client)] = $client;
        }
    }
    
    /**
     * Remove a client if it is stored in the current thread
     * 
     * @param Client $client
     */
    public function removeClient(Client $client)
    {
        if($this->hasClient($client))
        {
            unset($this->clients[spl_object_hash($client)]);
        }
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