<?php

require_once __DIR__ . '/vendor/autoload.php';

use Drmer\Reproxy\ReproxyServer;

$server = new ReproxyServer([
    // map your localhost to github server
    'tcp://127.0.0.1:443' => 'tcp://13.250.177.223:443',
]);

echo "Reverse proxy server starting\n";

$server->start();
// nothing will be executed after start