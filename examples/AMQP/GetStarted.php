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
