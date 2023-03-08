<?php 

namespace Kooriv\MessageBroker\RabbitMQ;

use Kooriv\MessageBroker\Contract\Consumer as ContractConsumer;
use Kooriv\MessageBroker\Exception\InvalidEvents;
use Kooriv\MessageBroker\Event\Consumers;
use Kooriv\MessageBroker\RabbitMQ\Lib\Channel;
use Kooriv\MessageBroker\RabbitMQ\Lib\Connection;
use Kooriv\MessageBroker\RabbitMQ\Lib\Helper;
use PhpAmqpLib\Connection\AbstractConnection;
use PhpAmqpLib\Channel\AMQPChannel;

class Consumer implements ContractConsumer
{
	private AbstractConnection $connection;
	private AMQPChannel $channel;
	private Helper $helper;
	private Consumers $events;

	public function __construct()
	{
		$this->connection = (new Connection)->create();
		$this->channel = (new Channel)->createChannel(connection: $this->connection);
		$this->setEvent();
	}

	// public function __destruct()
	// {
	// 	$this->channel->close();
	// 	$this->connection->close();
	// }

	public function handle(): void
	{
		$this->helper = new Helper(channel: $this->channel);

		$this
			->registerQueues()
			->loadBalancer()
			->registerSubscriber()
			->run();
	}

	private function registerQueues(): self
	{
		$this->events->rewind();
		/** @var \Kooriv\MessageBroker\Event\PubSub $event */
		foreach ($this->events as $event) {
			$this->helper->onQueue(
				queueName: $event->getQueueName(),
				exchangeName: $event->getExchangeName(),
				exchangeType: $event->getExchangeType(),
				routing_keys: $event->getRoutingKeys()
			);
		}
		
		return $this;
	}

	private function loadBalancer(): self
	{
		$this->helper->loadBalancer();

		return $this;
	}

	private function registerSubscriber(): self
	{
		$this->events->rewind();
		/** @var \Kooriv\MessageBroker\Event\PubSub $event */
		foreach ($this->events as $event) {
			$this->helper->subscribe(
				queue: $event->getQueueName(),
				callbacks: $event->getCallbacks()
			);
		}

		return $this;
	}

	private function run(): void
	{
		$this->helper->consume();
	}

	private function setEvent(): void
	{
		$events = config(key: 'amqp.events', default: Consumers::class);
		if (!is_subclass_of(object_or_class: $events, class: Consumers::class)) {
			throw new InvalidEvents("The events must be an instance of ".Consumers::class);
		}
		$this->events = new $events;
	}
}