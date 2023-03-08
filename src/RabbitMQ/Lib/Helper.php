<?php 

namespace Kooriv\MessageBroker\RabbitMQ\Lib;

use Kooriv\MessageBroker\RabbitMQ\Contract\Helper as ContractHelper;
use Kooriv\MessageBroker\Exception\ConsumerCallbackException;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;
use Kooriv\MessageBroker\Job as MainJob;

class Helper implements ContractHelper
{
	private MessageBroker $messageBroker;

	public function __construct(
		AMQPChannel $channel
	){
		$this->messageBroker = new MessageBroker(channel: $channel);
	}

	public function onQueue(
		string $queueName,
		string $exchangeName='',
		string $exchangeType='',
		array $routing_keys=[]
	): self
	{
		$this->messageBroker->createQueue(queue: $queueName);
		
		if (!empty($exchangeName)) {

			$this->messageBroker->createExchange(exchange: $exchangeName, type: $exchangeType);
			
			$routing_keys = count($routing_keys) ? $routing_keys : [''];
			foreach ($routing_keys as $routing_key) {
				$this->messageBroker->bindQueue(queue: $queueName, exchange: $exchangeName, routing_key: $routing_key);
			}

		}

		return $this;
	}

	public function publish(
		AMQPMessage $message,
		string $exchangeName='',
		array $routing_keys=[]
	): void
	{
		$routing_keys = count($routing_keys) ? $routing_keys : [''];

		foreach ($routing_keys as $routing_key) {
			$this->messageBroker->publish(
				message: $message,
				exchange: $exchangeName,
				routing_key: $routing_key
			);
		}
	}

	public function loadBalancer(int $prefetch_size=0, int $prefetch_count=1, bool $a_global=false): self
	{
		$this->messageBroker->balanceWorkerByProccess(
			prefetch_size: $prefetch_size,
			prefetch_count: $prefetch_count,
			a_global: $a_global
		);

		return $this;
	}

	public function subscribe(
		string $queue,
		array $callbacks=[],
	): self
	{
		foreach ($callbacks as $jobClass) {
			if (!is_subclass_of(object_or_class: $jobClass, class: MainJob::class)) {
				throw new ConsumerCallbackException("The callback must be an instance of " . MainJob::class);
			}

			$this->messageBroker->subscribe(
				queue: $queue,
				callback: [new $jobClass, 'handle'],
			);
		}

		return $this;
	}

	public function consume(): void
	{
		$this->messageBroker->consume();
	}
}