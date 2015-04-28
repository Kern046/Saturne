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
        
        EngineKernel::getInstance()->throwEvent(EventManager::NETWORK_SERVER_LISTENING, [
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
            if(($nbUpdatedStreams = stream_select($inputs, $outputs, $errors, 20)) == 0)
            {
                continue;
            }
            $this->treatInputs($inputs);
            $this->treatOutputs($outputs);
        }
    }
    
    /**
     * {@inheritdoc}
     */
    public function treatInputs($inputs)
    {
        $threadManager = EngineKernel::getInstance()->getThreadManager();
        
        foreach($inputs as $input)
        {
            $name = array_search($input, $this->inputs);
            
            if($name === 'master')
            {
                $this->acceptConnection($input);
                continue;
            }
            $threadManager->treatThreadInput($name);
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
        $connection = stream_socket_accept($input, null, $peername);
        
        $networkData = $this->getNetworkData([
            'connection' => $connection,
            'peername' => $peername,
        ]);
        
        EngineKernel::getInstance()->getClientManager()->getActionManager()->treatAction($networkData);
    }
    
    /**
     * {@inheritdoc}
     */
    public function getNetworkData($data)
    {
        $contents = json_decode(fread($data['connection'], 2048), true);
        $networkData = explode(':', $data['peername']);
        
        return [
            'action' => $contents['action'],
            'ip' => $networkData[0],
            'port' => $networkData[1],
            'user-agent' => $contents['user-agent']
        ];
    }
    
    public function shutdown($networkData)
    {
        $this->listen = false;
        
        EngineKernel::getInstance()->throwEvent(EventManager::NETWORK_SHUTDOWN, [
            'message' => 'Server will now shutdown due to user request'
        ]);
        EngineKernel::getInstance()->shutdown();
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