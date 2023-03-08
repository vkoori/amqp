<?php 

namespace Kooriv\MessageBroker\RabbitMQ;

use Kooriv\MessageBroker\Contract\AMQP as ContractAMQP;
use Kooriv\MessageBroker\Contract\Publisher as ContractPublisher;
use Kooriv\MessageBroker\Contract\Consumer as ContractConsumer;
use Kooriv\MessageBroker\Contract\JobHandler as ContractJob;
use Kooriv\MessageBroker\Enum\ExchangeType;
use Kooriv\MessageBroker\Exception\InvalidTopic;
use PhpAmqpLib\Exchange\AMQPExchangeType;

class RabbitMQ implements ContractAMQP
{
	public function publisher(): ContractPublisher
	{
		return new Publisher;
	}

	public function consumer(): ContractConsumer
	{
		return new Consumer;
	}

	public function job($message): ContractJob
	{
		return new Job(message: $message);
	}

	public function exchangeType(ExchangeType $type): string
	{
		return match (true) {
			ExchangeType::DIRECT == $type => AMQPExchangeType::DIRECT,
			ExchangeType::FANOUT == $type => AMQPExchangeType::FANOUT,
			ExchangeType::TOPIC == $type => AMQPExchangeType::TOPIC,
			ExchangeType::HEADERS == $type => AMQPExchangeType::HEADERS,
			default => throw new InvalidTopic("Topic handler not set!")
		};
	}
}