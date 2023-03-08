<?php 

namespace Kooriv\MessageBroker\Event;

use Kooriv\MessageBroker\Contract\PubSub as ContractPubSub;
use Kooriv\MessageBroker\Enum\ExchangeType;
use Kooriv\MessageBroker\Contract\AMQP;

class PubSub implements ContractPubSub
{
	protected string $queueName='';
	protected string $exchangeName='';
	protected ?ExchangeType $exchangeType=null;
	protected array $routing_keys=[];
	protected array $callbacks=[];

	public function getQueueName(): string
	{
		return $this->queueName;
	}

	public function getExchangeName(): string
	{
		return $this->exchangeName;
	}

	public function getExchangeType(): string
	{
		if (is_null($this->exchangeType)) {
			return '';
		}

		/** @var AMQP $amqp */
		$amqp = app(AMQP::class);
		return $amqp->exchangeType(type: $this->exchangeType);
	}

	public function getRoutingKeys(): array
	{
		return $this->routing_keys;
	}

	public function getCallbacks(): array
	{
		return $this->callbacks;
	}
}