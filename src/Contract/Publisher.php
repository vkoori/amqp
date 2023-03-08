<?php 

namespace Kooriv\MessageBroker\Contract;

use Kooriv\MessageBroker\Event\PubSub;

interface Publisher
{
	public function dispatch(string $message, array $properties=[]): self;

	public function onQueue(PubSub $queue): void;
}