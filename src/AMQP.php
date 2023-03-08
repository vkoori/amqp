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
		app()->configure('amqp');
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