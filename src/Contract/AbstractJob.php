<?php 

namespace Kooriv\MessageBroker\Contract;

interface AbstractJob
{
	public function handle($msg):void;

	public function payload(MainJob $event);

	public function exceptionHandler(
		\Throwable $e,
		string $queueName,
		string $exchangeName='',
		?\Kooriv\MessageBroker\Enum\ExchangeType $exchangeType=null,
		array $routing_keys=[]
	);
}