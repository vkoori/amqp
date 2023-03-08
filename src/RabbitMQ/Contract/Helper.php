<?php 

namespace Kooriv\MessageBroker\RabbitMQ\Contract;

use PhpAmqpLib\Message\AMQPMessage;

interface Helper
{
	public function onQueue(
		string $queueName,
		string $exchangeName='',
		string $exchangeType='',
		array $routing_keys=[]
	): self;

	public function publish(
		AMQPMessage $message,
		string $exchangeName='',
		array $routing_keys=[]
	): void;

	public function loadBalancer(
		int $prefetch_size=0,
		int $prefetch_count=1,
		bool $a_global=false
	): self;

	public function subscribe(
		string $queue,
		array $callbacks=[],
	): self;

	public function consume(): void;
}