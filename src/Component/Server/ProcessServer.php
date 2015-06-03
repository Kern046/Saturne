<?php

namespace Saturne\Component\Server;

class ProcessServer extends AbstractServer
{
    public function listen()
    {
        $this->addInput('mainstream', stream_socket_server('tcp://0.0.0.0:8890', $errno, $errstr));
        
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
    
    public function acceptConnection($input)
    {
        $connection = stream_socket_accept($input, null, $peername);
        
        $networkData = $this->getNetworkData([
            'connection' => $connection,
            'peername' => $peername,
        ]);
        
        $this->engine->get('saturne.client_action_manager')->treatAction($networkData);
    }
    
    public function closeConnection() {
        ;
    }
    
    public function pingPort()
    {
        
    }
    
    public function getNewPort()
    {
        
    }
    
    public function shutdown($networkData)
    {
        $this->listen = false;
        
        $this->engine->throwEvent(EventManager::NETWORK_SHUTDOWN, [
            'message' => 'Server will now shutdown due to user request'
        ]);
        $this->engine->shutdown();
    }
}