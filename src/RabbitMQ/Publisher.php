<?php 

namespace Kooriv\MessageBroker\RabbitMQ;

use Kooriv\MessageBroker\Contract\Publisher as ContractPublisher;
use Kooriv\MessageBroker\Event\PubSub;
use Kooriv\MessageBroker\Exception\PublishException;
use Kooriv\MessageBroker\RabbitMQ\Lib\Channel;
use Kooriv\MessageBroker\RabbitMQ\Lib\Connection;
use Kooriv\MessageBroker\RabbitMQ\Lib\Message;
use Kooriv\MessageBroker\RabbitMQ\Lib\Helper;
use PhpAmqpLib\Connection\AbstractConnection;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;

class Publisher implements ContractPublisher
{
	private AbstractConnection $connection;
	private AMQPChannel $channel;
	private ?AMQPMessage $message;

	public function __construct()
	{
		$this->connection = (new Connection)->create();
		$this->channel = (new Channel)->createChannel(connection: $this->connection);
	}

	public function __destruct()
	{
		$this->channel->close();
		$this->connection->close();
	}

	public function dispatch(string $message, array $properties=[]): self
	{
		$this->message = Message::create(message: $message, properties: $properties);

		return $this;
	}

	public function onQueue(PubSub $queue): void
	{
		if (is_null($this->message)) {
			throw new PublishException("Please set your message using the dispatch method");
		}
		
		$helper = new Helper(channel: $this->channel);

		$helper->onQueue(
			queueName: $queue->getQueueName(),
			exchangeName: $queue->getExchangeName(),
			exchangeType: $queue->getExchangeType(),
			routing_keys: $queue->getRoutingKeys()
		);

		$helper->publish(
			message: $this->message,
			exchangeName: $queue->getExchangeName(),
			routing_keys: empty($queue->getExchangeName()) ? [$queue->getQueueName()] : $queue->getRoutingKeys()
		);
	}
}