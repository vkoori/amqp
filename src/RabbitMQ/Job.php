<?php 

namespace Kooriv\MessageBroker\RabbitMQ;

use Kooriv\MessageBroker\Contract\JobHandler as ContractJob;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;

class Job implements ContractJob
{
	public function __construct(private AMQPMessage $message)
	{}

	public function getChannel(): AMQPChannel
	{
		return $this->message->getChannel();
	}

	public function getConsumerTag(): ?string
	{
		return $this->message->getConsumerTag();
	}

	public function getExchange(): ?string
	{
		return $this->message->getExchange();
	}

	public function getRoutingKey(): ?string
	{
		return $this->message->getRoutingKey();
	}

	public function get_properties(): array
	{
		return $this->message->get_properties();
	}

	public function getBody(): string
	{
		return $this->message->getBody();
	}

	public function ack(): void
	{
		$this->message->ack();
	}
}