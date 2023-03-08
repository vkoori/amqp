# Installation

## install

```shell
composer require vkoori/amqp
```

## add service providers

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

## generate config

In laravel application you can use this command.

```shell
php artisan vendor:publish --provider="Kooriv\MessageBroker\Providers\Config" --tag="config"
```

In lumen application create the `config/amqp.php` file with the following content.

```shell
<?php

use Kooriv\MessageBroker\RabbitMQ\Enum\Connections;
use Kooriv\MessageBroker\Event\Consumers;
use Kooriv\MessageBroker\Enum\ExchangeType;

return [

    'driver' => env('AMQP_DRIVER', 'sync'),
    'events' => Consumers::class,

    'rabbitMQ' => [
        'connection_type' => Connections::STREAM,
        'hosts' => [
            [
                'host' => env('RABBITMQ_HOST', '127.0.0.1'),
                'port' => env('RABBITMQ_PORT', 5672),
                'user' => env('RABBITMQ_USER', 'guest'),
                'password' => env('RABBITMQ_PASSWORD', 'guest'),
                'vhost' => env('RABBITMQ_VHOST', '/'),
            ]
        ],
        'ssl_options' => [
            'cafile' => env('RABBITMQ_SSL_CA_FILE', null),
            'local_cert' => env('RABBITMQ_SSL_PUBLIC_FILE', null),
            'local_pk' => env('RABBITMQ_SSL_PRIVATE_FILE', null),
            'verify_peer' => env('RABBITMQ_SSL_VERIFY_PEER', true),
            'verify_peer_name' => env('RABBITMQ_SSL_PASSPHRASE', false),
        ],
    ],

    // if you want to publish failed jobs in to the message broker, use the `failed_jobs` key
    'failed_jobs' => [
        'queueName' => env(key:'APP_NAME', default: 'amqp') . '_failed_job',
        'exchangeName' =>  env(key:'APP_NAME', default: 'amqp') . '_failed_job',
        'exchangeType' => ExchangeType::TOPIC,
        'routing_keys' => [
            env(key:'APP_NAME', default: 'amqp').'amqp_job.failed'
        ]
    ],
];
```

# Get Started

## publish message in message broker

You can access `AMQP` by dependency injection or by using `app(AMQP::class)`.

```shell
<?php

namespace App\Http\Controllers\AMQP;

use App\Http\Controllers\AMQP\Events\FirstQueue;
use Kooriv\MessageBroker\Contract\AMQP;

class GetStarted
{
    public function publish(AMQP $amqp)
    {
        $amqp
        ->publisher()
        ->dispatch(message: "test message", properties: [])
        ->onQueue(queue: new FirstQueue);
    }
}
```

You need create `FirstQueue` with following content.

```shell
<?php

namespace App\Http\Controllers\AMQP\Events;

use App\Http\Controllers\AMQP\Listeners\FirstJob;
use Kooriv\MessageBroker\Enum\ExchangeType;
use Kooriv\MessageBroker\Event\PubSub;

class FirstQueue extends PubSub
{
    protected string $queueName = 'queueName';
    protected string $exchangeName = 'exchangeName';
    protected ?ExchangeType $exchangeType = ExchangeType::TOPIC;
    protected array $routing_keys = [
        'payment_service.invoice.paid'
    ];
    protected array $callbacks=[
        FirstJob::class
    ];

}

```

> **Note**
> If you don't need consume this event don't need to set `$callbacks`

## subscribe messages and run them

> **Note**
> First of all, it is necessary to update `events` key in `config/amqp.php` file and create a class with the same path

```shell
<?php

return [
    ...
    'events' => \App\Http\Controllers\AMQP\Consumers\Consumers::class,
    ...
];
```

```shell
<?php

namespace App\Http\Controllers\AMQP\Consumers;

use App\Http\Controllers\AMQP\Events\FirstQueue;
use Kooriv\MessageBroker\Event\Consumers as EventConsumers;

class Consumers extends EventConsumers
{
    protected array $pubSub = [
        FirstQueue::class
    ];
}
```

For subscribing, we need to create the classes in the `callback` method of the `FirstQueue` class.
These classes should extend the `Kooriv\MessageBroker\Job` class.

```shell
<?php

namespace App\Http\Controllers\AMQP\Listeners;

use Kooriv\MessageBroker\Contract\MainJob;
use Kooriv\MessageBroker\Job;

class FirstJob extends Job
{
	public function payload(MainJob $event)
	{
		dump(
			$event->getAMQP(),
			$event->getBody(),
			// $event->getChannel(),
			$event->getConsumerTag(),
			$event->getExchange(),
			$event->getRoutingKey(),
			$event->get_properties()
		);
	}
}
```

Enter the following command to run the subscription.

```shell
php artisan consume
```

# Driver Supports:

1. RabbitMQ

# Comming Soon

-   Kafka
-   Pulsar
