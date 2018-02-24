# Reproxy

PHP reverse proxy, map your IPv6 address to an IPv4 address or domain. Support both HTTP and HTTPS

## Usage
### normal use
```sh
$ git clone https://github.com/jndrm/reproxy.git && cd reproxy
$ composer install
```
replace your proxy configuration in ```start.php``` file
```sh
$ sudo php start.php
```
or use it in your own project
```php
<?php
    use Drmer\Reproxy\ReproxyServer;

    $server = new ReproxyServer([
        // map your localhost to github server
        'tcp://127.0.0.1:443' => 'tcp://13.250.177.223:443',
    ]);

    echo "Reverse proxy server starting\n";

    $server->start();
    // nothing will be executed after start
```

### Laravel
add reproxy to your project
```sh
$ composer require drmer/reproxy
```
Add service provider to your ```config/app.php``` file
```php
Drmer\Reproxy\ReproxyServiceProvider::class,
```
publish config file
```sh
$ php artisan vendor:publish --provider="Drmer\Reproxy\ReproxyServiceProvider"
```
add your proxy map to ```config/reproxy.php``` file
```php
'tcp://[::]:443' => 'tcp://8.8.8.8:443',
```
```sh
$ sudo php artisan reproxy
```
## Todo
- [ ] Add global composer command
- [ ] Add tests
- [ ] Add Lumen support
- [ ] Add Laravel 4 Support