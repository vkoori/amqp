<?php 

namespace Kooriv\MessageBroker\RabbitMQ\Lib;

use Kooriv\MessageBroker\RabbitMQ\Contract\Channel as ContractChannel;
use PhpAmqpLib\Connection\AbstractConnection;
use PhpAmqpLib\Channel\AMQPChannel;

class Channel implements ContractChannel
{
	public function createChannel(AbstractConnection $connection): AMQPChannel
	{
		return $connection->channel();
	}

	// public function closeChannel(AMQPChannel $channel)
	// {
	// 	$channel->close();
	// }
}