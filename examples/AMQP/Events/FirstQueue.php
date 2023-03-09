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
