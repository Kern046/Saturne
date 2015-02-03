<?php
/**
 * Representation of the data symbolizing a client of the application.
 * 
 * This can be viewed as a connection, and musn't be confused with the notion
 * of an application user, usually meant as an registered account inside the
 * application. 
 * 
 * The following object is focused on the connection data, used to perform
 * network operations between the engine and the application user.
 */
namespace Saturne\Model;

/**
 * @name Client
 * @author Axel Venet <axel-venet@developtech.fr>
 */
class Client
{
    /** @var string **/
    private $ip;
    /** @var integer **/
    private $port;
    /** @var string **/
    private $userAgent;
    /** @var \DateTime **/
    private $connectionStart;
    
    /**
     * Set the DateTime of the connection creation
     */
    public function __construct()
    {
        $this->connectionStart = new \DateTime();
    }
    
    /**
     * Set the IP address
     * 
     * @param string $ip
     * @return Client
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
        
        return $this;
    }
    
    /**
     * Get the IP address
     * 
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }
    
    /**
     * Set the client port
     * 
     * @param integer $port
     * @return Client
     */
    public function setPort($port)
    {
        $this->port = $port;
        
        return $this;
    }
    
    /**
     * Get the client port
     * 
     * @return integer
     */
    public function getPort()
    {
        return $this->port;
    }
    
    /**
     * Set the client user-agent
     * 
     * @param string $userAgent
     * @return Client
     */
    public function setUserAgent($userAgent)
    {
        $this->userAgent = $userAgent;
        
        return $this;
    }
    
    /**
     * Get the client user-agent
     * 
     * @return string
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }
    
    /**
     * Get the connection creation DateTime
     * 
     * @return \DateTime
     */
    public function getConnectionStart()
    {
        return $this->connectionStart();
    }
}