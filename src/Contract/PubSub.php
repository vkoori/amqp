<?php 

namespace Kooriv\MessageBroker\Contract;

interface PubSub
{
	public function getQueueName(): string;

	public function getExchangeName(): string;

	public function getExchangeType(): string;

	public function getRoutingKeys(): array;

	public function getCallbacks(): array;
}