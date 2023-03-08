<?php

namespace Kooriv\MessageBroker\Providers;

use Illuminate\Support\ServiceProvider;

class AMQP extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            abstract: \Kooriv\MessageBroker\Contract\AMQP::class,
            concrete: fn ($app) => (new \Kooriv\MessageBroker\AMQP(container: $app))->driver()
        );
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                \Kooriv\MessageBroker\Commands\Consume::class,
            ]);
        }
    }
}