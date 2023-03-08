<?php 

namespace Kooriv\MessageBroker\RabbitMQ\Lib;

use Kooriv\MessageBroker\RabbitMQ\Contract\Message as ContractMessage;
use PhpAmqpLib\Message\AMQPMessage;

class Message implements ContractMessage
{
	public static function create(
		string $message,
		array $properties=[]
	): AMQPMessage
	{
		return new AMQPMessage(
			body: $message,
			properties: [
				'content_type' => 'text/plain',
				'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT,
				...$properties
			]
		);
	}
}