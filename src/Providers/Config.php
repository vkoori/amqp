<?php

namespace Kooriv\MessageBroker\Providers;

use Illuminate\Support\ServiceProvider;

class Config extends ServiceProvider
{
    private $amqpConfig = __DIR__ . '/../Config/amqp.php';

    public function register()
    {
        $this->mergeConfigFrom(path: $this->amqpConfig, key: 'amqp');
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes(
                paths: [
                    $this->amqpConfig => base_path(path: '/config/amqp.php'),
                ],
                groups: 'config'
            );
        }
    }
}