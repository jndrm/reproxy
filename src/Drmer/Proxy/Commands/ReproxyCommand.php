<?php

namespace Drmer\Reproxy\Commands;

use Illuminate\Console\Command;
use React\Socket\Server;
use React\EventLoop\Factory as LoopFactory;
use React\Socket\ConnectionInterface;
use Drmer\Reproxy\TcpClient;

class ReproxyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reproxy';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'start ipv6 reverse proxy';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    protected $loop;

    protected $proxies;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->loop = LoopFactory::create();

        $this->proxies = (array) config('reproxy.map');

        if (!count($this->proxies)) {
            $this->error("no proxies found");
            return;
        }

        foreach ((array)$this->proxies as $key => $value) {
            $server = new Server($key, $this->loop);
            $server->on('connection', [$this, 'onConnection']);
            $server->on('error', 'printf');
        }

        $this->comment("IPv6 reverse proxy starting...");

        $this->loop->run();
    }

    public function onConnection($conn)
    {
        $local = $conn->getLocalAddress();
        $this->info("new connection on {$local}");
        $uri = isset($this->proxies[$local]) ? $this->proxies[$local] : null;
        if (!$uri) {
            $this->error("no reproxy found");
            $conn->close();
            return;
        }
        with(new TcpClient($this->loop, $conn))->start($uri);
    }
}