<?php

namespace MedianetDev\CloudMessage;

use Illuminate\Support\ServiceProvider;

class CloudMessageServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        // if ($this->app->runningInConsole() && function_exists('config_path')) {  // function not available and 'publish' not relevant in Lumen
        $this->publishes([
            $this->getConfigFile() => config_path('cloud_message.php'),
        ], 'config');

        // }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom($this->getConfigFile(), 'cloud_message');
    }

    /**
     * @return string
     */
    protected function getConfigFile(): string
    {
        return __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'cloud_message.php';
    }
}
