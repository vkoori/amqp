# Installation

install

```shell
composer require vkoori/amqp
```

add service providers

In laravel application add this lines to your `config/app.php` file.

```shell
'providers' => [
    //...
    \Kooriv\MessageBroker\Providers\AMQP::class,
    \Kooriv\MessageBroker\Providers\Config::class
]
```

In lumen application add this lines to your `bootstrap/app.php` file.

```shell
$app->register(\Kooriv\MessageBroker\Providers\AMQP::class);
$app->register(\Kooriv\MessageBroker\Providers\Config::class);
```

generate config

```shell
php artisan vendor:publish --provider="Kooriv\MessageBroker\Providers\Config" --tag="config"
```

# Driver Supports:

1. RabbitMQ
