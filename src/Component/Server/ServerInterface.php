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
     * {@inheritdoc}
     */
    public function acceptConnection();
    
    /**
     * {@inheritdoc}
     */
    public function closeConnection();
    
    /**
     * {@inheritdoc}
     */
    public function addInput();
    
    /**
     * {@inheritdoc}
     */
    public function getInput();
    
    /**
     * {@inheritdoc}
     */
    public function removeInput();
    
    /**
     * {@inheritdoc}
     */
    public function getInputs();
    
    /**
     * {@inheritdoc}
     */
    public function addOutput();
    
    /**
     * {@inheritdoc}
     */
    public function getOutput();
    
    /**
     * {@inheritdoc}
     */
    public function removeOutput();
    
    /**
     * {@inheritdoc}
     */
    public function getOutputs();
}