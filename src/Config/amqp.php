<?php

use Kooriv\MessageBroker\RabbitMQ\Enum\Connections;
use Kooriv\MessageBroker\Event\Consumers;
use Kooriv\MessageBroker\Enum\ExchangeType;

return [

    'driver' => env('AMQP_DRIVER', 'sync'),

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
        'events' => Consumers::class,
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