<?php

namespace Saturne\Component\Server;

use Saturne\Component\Event\EventManager;

/**
 * @name Server
 * @author Axel Venet <axel-venet@developtech.fr>
 */
class MasterServer extends AbstractServer
{
    /**
     * {@inheritdoc}
     */
    public function listen()
    {
        $this->addInput('master', stream_socket_server('tcp://0.0.0.0:8889', $errno, $errstr));
        
        $this->engine->throwEvent(EventManager::NETWORK_SERVER_LISTENING, [
            'protocol' => 'tcp',
            'ip' => '0.0.0.0',
            'port' => 8889,
            'message' => 'The server is now listening at tcp://0.0.0.0:8889'
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
        $processManager = $this->engine->get('saturne.process_manager');
        
        foreach($inputs as $input)
        {
            $name = array_search($input, $this->inputs);
            
            if($name === 'master')
            {
                $this->acceptConnection($input);
                continue;
            }
            $processManager->treatProcessInput($name);
        }
    }
    
    /**
     * {@inheritdoc}
     */
    public function treatOutputs($outputs)
    {
        foreach($outputs as $output)
        {
            $name = array_search($output, $this->outputs);
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
        
        $this->engine->get('saturne.client_action_manager')->treatAction($networkData);
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
        
        $this->engine->throwEvent(EventManager::NETWORK_SHUTDOWN, [
            'message' => 'Server will now shutdown due to user request'
        ]);
        $this->engine->shutdown();
    }
    
    /**
     * {@inheritdoc}
     */
    public function closeConnection()
    {
        
    }
}