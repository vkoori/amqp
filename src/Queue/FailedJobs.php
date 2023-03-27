<?php 

namespace Kooriv\MessageBroker\Queue;

use Kooriv\MessageBroker\Event\PubSub;
use Kooriv\MessageBroker\Enum\ExchangeType;

class FailedJobs extends PubSub
{
	public function __construct(
		string $queueName='',
		string $exchangeName='',
		?ExchangeType $exchangeType=null,
		array $routing_keys=[],
		array $callbacks=[],
	)
	{
		$this->queueName = $queueName;
		$this->exchangeName = $exchangeName;
		$this->exchangeType = $exchangeType;
		$this->routing_keys = $routing_keys;
		$this->callbacks = $callbacks;
	}
}