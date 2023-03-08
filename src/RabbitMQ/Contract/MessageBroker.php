<?php 

namespace Kooriv\MessageBroker\RabbitMQ\Contract;

use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire\AMQPTable;

interface MessageBroker
{
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
	): self;

	public function createQueue(
		string $queue,
		bool $passive = false,
		bool $durable = true,
		bool $exclusive = false,
		bool $auto_delete = false,
		bool $nowait = false,
		AMQPTable|array $arguments = [],
		?int $ticket = null
	): self;

	public function bindQueue(
		string $queue,
		string $exchange,
		string $routing_key,
		bool $nowait = false,
		AMQPTable|array $arguments = [],
		?int $ticket = null
	): self;

	public function balanceWorkerByProccess(
		int $prefetch_size=0,
		int $prefetch_count=1,
		bool $a_global=false
	): self;

	public function publish(
		AMQPMessage $message,
		string $exchange='',
		string $routing_key='',
		bool $mandatory=false,
		bool $immediate=false,
		?int $ticket = null
	): void;

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
	): self;

	public function consume(int $maximumPoll=10): void;
}