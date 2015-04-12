<?php

namespace Saturne\Component\Server;

/**
 * @name ServerInterface
 * @author Axel Venet <axel-venet@developtech.fr>
 */
interface ServerInterface
{
    /**
     * {@inheritdoc}
     */
    public function listen();
    
    /**
     * @param resource $input
     */
    public function acceptConnection($input);
    
    /**
     * {@inheritdoc}
     */
    public function closeConnection();
    
    /**
     * Register the input related to the given name
     * Throws an exception if the name is already affected
     * 
     * @param string $name
     * @param resource $input
     * @throws InvalidArgumentException
     */
    public function addInput($name, $input);
    
    /**
     * CHeck if an input is associated to the given name
     * 
     * @param $name
     * @return boolean
     */
    public function hasInput($name);
    
    /**
     * Get the given name's associated input
     * 
     * @param string $name
     * @return resource
     * @throws InvalidArgumentException
     */
    public function getInput($name);
    
    /**
     * Remove the given input
     * 
     * @param string $name
     */
    public function removeInput($name);
    
    /**
     * Return all the registered inputs
     * 
     * @return array
     */
    public function getInputs();
    
    /**
     * Get all the inputs into a linear array of resources
     * 
     * @return array
     */
    public function getMappedInputs();
    
    /**
     * Add an output registered by name in the outputs array
     * Throws an exception if the given name is already affected
     * 
     * @param string $name
     * @param resource $output
     * @throws InvalidArgumentException
     */
    public function addOutput($name, $output);
    
    /**
     * Check if an output is registered for the given name
     * 
     * @param string $name
     * @return boolean
     */
    public function hasOutput($name);
    
    /**
     * Get the requested output if it exists
     * Throws an exception otherwise
     * 
     * @param string $name
     * @return resource
     * @throws InvalidArgumentException
     */
    public function getOutput($name);
    
    /**
     * Remove the given output
     * 
     * @param string $name
     */
    public function removeOutput($name);
    
    /**
     * Return all the registered outputs
     * 
     * @return array
     */
    public function getOutputs();
    
    /**
     * Get all the outputs into a linear array of resources
     * 
     * @return array
     */
    public function getMappedOutputs();
}