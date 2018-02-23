# Reproxy

PHP reverse proxy, map your IPv6 address to an IPv4 address or domain. Support both HTTP and HTTPS

## Installation

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

## Usage
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