<?php 

namespace Kooriv\MessageBroker\RabbitMQ\Lib;

use Kooriv\MessageBroker\RabbitMQ\Contract\MessageBroker as ContractMessageBroker;
use PhpAmqpLib\Channel\AMQPChannel;
use Kooriv\MessageBroker\Exception\InvalidExchange;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire\AMQPTable;
use ReflectionClass;

class MessageBroker implements ContractMessageBroker
{
	public function __construct(
		private AMQPChannel $channel
	){}

	public function createExchange(
		string $exchange,
		string $type,
		bool $passive = false,
		bool $durable = true,
		bool $auto_delete = false,
		bool $internal = false,
		bool $nowait = false,
		AMQPTable|array $arguments = [],
		?int $ticket = null
	): self
	{
		$this->checkExchangeType(type: $type);

		$this->channel->exchange_declare(
			exchange: $exchange,
			type: $type, # AMQPExchangeType::TOPIC,
			passive: $passive,
			durable: $durable,
			auto_delete: $auto_delete,
			internal: $internal,
			nowait: $nowait,
			arguments: $arguments,
			ticket: $ticket
		);

		return $this;
	}

	public function createQueue(
		string $queue,
		bool $passive = false,
		bool $durable = true,
		bool $exclusive = false,
		bool $auto_delete = false,
		bool $nowait = false,
		AMQPTable|array $arguments = [],
		?int $ticket = null
	): self
	{
		$this->channel->queue_declare(
			queue: $queue,
			passive: $passive,
			durable: $durable,
			exclusive: $exclusive,
			auto_delete: $auto_delete,
			nowait: $nowait,
			arguments: $arguments,
			ticket: $ticket
		);

		return $this;
	}

	public function bindQueue(
		string $queue,
		string $exchange,
		string $routing_key,
		bool $nowait = false,
		AMQPTable|array $arguments = [],
		?int $ticket = null
	): self
	{
		$this->channel->queue_bind(
			queue: $queue,
			exchange: $exchange,
			routing_key: $routing_key,
			nowait: $nowait,
			arguments: $arguments,
			ticket: $ticket
		);

		return $this;
	}

	public function balanceWorkerByProccess(int $prefetch_size=0, int $prefetch_count=1, bool $a_global=false): self
	{
		$this->channel->basic_qos(prefetch_size: $prefetch_size, prefetch_count: $prefetch_count, a_global: $a_global);

		return $this;
	}

	public function publish(
		AMQPMessage $message,
		string $exchange='',
		string $routing_key='',
		bool $mandatory=false,
		bool $immediate=false,
		?int $ticket = null
	): void
	{
		$this->channel->basic_publish(
			msg: $message,
			exchange: $exchange,
			routing_key: $routing_key,
			mandatory: $mandatory, 
			immediate: $immediate, 
			ticket: $ticket
		);
	}

	public function subscribe(
		string $queue,
		callable $callback,
		string $consumer_tag='',
		bool $no_local=false,
		bool $no_ack=false,
		bool $exclusive=false,
		bool $nowait=false,
		?int $ticket=null,
		AMQPTable|array $arguments=[]
	): self
	{
		$this->channel->basic_consume(
			queue: $queue,
			consumer_tag: $consumer_tag,
			no_local: $no_local,
			no_ack: $no_ack,
			exclusive: $exclusive,
			nowait: $nowait,
			callback: $callback,
			ticket: $ticket,
			arguments: $arguments
		);

		return $this;
	}

	public function consume(int $maximumPoll=10): void
	{
		$this->channel->consume(maximumPoll: $maximumPoll);
	}

	private function checkExchangeType($type): void
	{
		$AMQPExchangeType = new ReflectionClass(objectOrClass: AMQPExchangeType::class);
		if (
			!in_array(needle: $type, haystack: $AMQPExchangeType->getConstants())
		) {
			throw new InvalidExchange(
				message: "The ExchangeType can accept one of the expressions "
				.implode(
					separator:',', 
					array: $AMQPExchangeType->getConstants()
				)
			);
		}
	}
}