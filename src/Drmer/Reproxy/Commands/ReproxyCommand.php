<?php

namespace Drmer\Reproxy\Commands;

use Illuminate\Console\Command;
use Drmer\Reproxy\ReproxyServer;

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

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $proxies = (array) config('reproxy.map');

        if (!count($proxies)) {
            $this->error("no proxies found");
            return;
        }

        $this->comment("IPv6 reverse proxy starting...");

        with(new ReproxyServer($proxies))->start();

        $this->loop->run();
    }
}