<?php 

namespace Kooriv\MessageBroker\RabbitMQ\Contract;

use PhpAmqpLib\Message\AMQPMessage;

interface Message
{
	public static function create(
		string $message,
		array $properties=[]
	): AMQPMessage;
}