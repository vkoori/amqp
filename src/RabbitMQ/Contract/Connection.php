<?php 

namespace Kooriv\MessageBroker\RabbitMQ\Contract;

interface Connection
{
	public function create();
	// public function close(\PhpAmqpLib\Connection\AbstractConnection $connection);
}