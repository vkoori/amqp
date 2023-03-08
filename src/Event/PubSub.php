<?php 

namespace Kooriv\MessageBroker\Event;

use Kooriv\MessageBroker\Contract\PubSub as ContractPubSub;
use Kooriv\MessageBroker\Enum\ExchangeType;
use Kooriv\MessageBroker\Contract\AMQP;

class PubSub implements ContractPubSub
{
	private string $queueName;
	private string $exchangeName='';
	private ?ExchangeType $exchangeType=null;
	private array $routing_keys=[];
	private array $callbacks=[];

	public function queue(
		string $queueName,
		string $exchangeName='',
		?ExchangeType $exchangeType=null,
		array $routing_keys=[],
	): self
	{
		$this->queueName = $queueName;
		$this->exchangeName = $exchangeName;
		$this->exchangeType = $exchangeType;
		$this->routing_keys = $routing_keys;

		return $this;
	}

	public function callback(array $callbacks): self
	{
		$this->callbacks = $callbacks;

		return $this;
	}

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