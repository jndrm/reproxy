<?php

namespace Drmer\Reproxy;

use React\Socket\Server;
use React\EventLoop\Factory as LoopFactory;
use React\Socket\ConnectionInterface;
use Drmer\Reproxy\TcpClient;

class ReproxyServer
{
    protected $loop;

    protected $proxies;

    public function __construct($proxies)
    {
        $this->loop = LoopFactory::create();

        $this->proxies = $proxies;

        foreach ($proxies as $key => $value) {
            $server = new Server($key, $this->loop);
            $server->on('connection', [$this, 'onConnection']);
            $server->on('error', 'printf');
        }
    }

    public function start()
    {
        $this->loop->run();
    }

    public function onConnection($conn)
    {
        $local = $conn->getLocalAddress();
        echo "new connection on {$local}\n";
        $uri = isset($this->proxies[$local]) ? $this->proxies[$local] : null;
        if (!$uri) {
            echo "no reproxy found\n";
            $conn->close();
            return;
        }
        (new TcpClient($this->loop, $conn))->start($uri);
    }
}