<?php

namespace Drmer\Reproxy;

use Illuminate\Support\ServiceProvider;

class ReproxyServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            $this->configPath() => config_path('reproxy.php'),
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->commands([
            Commands\ReproxyCommand::class,
        ]);

        $this->mergeConfigFrom($this->configPath(), 'Reproxy');
    }

    protected function configPath()
    {
        return __DIR__ . '/../../../config/reproxy.php';
    }
}
