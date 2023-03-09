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