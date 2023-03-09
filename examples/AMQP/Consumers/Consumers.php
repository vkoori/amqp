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
