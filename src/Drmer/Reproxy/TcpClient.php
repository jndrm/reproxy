<?php

namespace Drmer\Reproxy;

use React\Socket\Connector;

class TcpClient
{
    public $socket = null;

    public $recvBuffer = [];

    private $connected = false;

    private $conn = null;

    public function __construct($loop, $conn)
    {
        $this->socket = new Connector($loop);

        $conn->on('data', [$this, 'onData']);

        $this->conn = $conn;
    }

    public function start($uri)
    {
        $this->socket->connect($uri)->then(function($cli) {
            $this->connected = true;

            $cli->pipe($this->conn);

            while ($buffer = array_shift($this->recvBuffer)) {
                $cli->write($buffer);
            }

            $this->conn->pipe($cli);
        }, 'printf');
    }

    public function onData($data)
    {
        if (!$data) {
            return;
        }
        if ($this->isConnected()) {
            return;
        }
        array_push($this->recvBuffer, $data);
    }

    public function isConnected()
    {
        return $this->connected;
    }
}