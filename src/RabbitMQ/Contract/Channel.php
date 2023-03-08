<?php 

namespace Kooriv\MessageBroker\RabbitMQ\Contract;

use PhpAmqpLib\Connection\AbstractConnection;

interface Channel
{
	public function createChannel(AbstractConnection $connection);
	// public function closeChannel(AMQPChannel $channel);
}