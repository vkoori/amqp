<?php 

namespace Kooriv\MessageBroker\Contract;

use Kooriv\MessageBroker\Enum\ExchangeType;

interface PubSub
{
	public function queue(
		string $queueName,
		string $exchangeName='',
		?ExchangeType $exchangeType=null,
		array $routing_keys=[],
	): self;

	public function callback(array $callbacks): self;

	public function getQueueName(): string;

	public function getExchangeName(): string;

	public function getExchangeType(): string;

	public function getRoutingKeys(): array;

	public function getCallbacks(): array;
}