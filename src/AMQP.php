<?php 

namespace Kooriv\MessageBroker;

use Illuminate\Support\Manager;
use Kooriv\MessageBroker\RabbitMQ\RabbitMQ;

class AMQP extends Manager
{
	public function channel(string $name): \Kooriv\MessageBroker\Contract\AMQP
	{
		return $this->driver(driver: $name);
	}

	public function getDefaultDriver(): string
	{
		if (get_class(object: app()) == 'Laravel\Lumen\Application') {
			app()->configure('amqp');
		}
		return $this->config->get('amqp.driver');
	}

	public function driver($driver = null): \Kooriv\MessageBroker\Contract\AMQP
	{
		return parent::driver(driver: $driver);
	}

	public function createRabbitMQDriver()
	{
		return new RabbitMQ;
	}
}