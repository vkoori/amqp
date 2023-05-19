<?php 

namespace Kooriv\MessageBroker;

use Kooriv\MessageBroker\Contract\AbstractJob;
use Kooriv\MessageBroker\Contract\MainJob;
use Kooriv\MessageBroker\Contract\AMQP;
use Kooriv\MessageBroker\Contract\JobHandler;
use Illuminate\Support\Facades\Log;
use Kooriv\MessageBroker\Queue\FailedJobs;
use Throwable;

abstract class Job implements AbstractJob, MainJob
{
	private AMQP $amqp;
	private JobHandler $job;

	public function handle($message): void
	{
		try {
			$this->amqp = app(AMQP::class);
			$this->job = $this
				->getAMQP()
				->job(message: $message);

			$this->payload(event: $this);

			$this->job->ack();
		} catch (Throwable $e) {
			Log::error(message: $e);

			if ( $failed_jobs = config(key: 'amqp.failed_jobs', default: false) ) {
				if ( !isset($failed_jobs['exchangeName']) || !isset($failed_jobs['exchangeType']) ) {
					$failed_jobs['exchangeName'] = $failed_jobs['exchangeType'] = '';
				}
				$this->exceptionHandler(
					e: $e,
					queueName: $failed_jobs['queueName'] ?? env(key:'APP_NAME', default: 'amqp') . '_failed_job',
					exchangeName: $failed_jobs['exchangeName'],
					exchangeType: $failed_jobs['exchangeType'] ?? null,
					routing_keys: $failed_jobs['routing_keys'] ?? []
				);
			}
		}
	}

	public function exceptionHandler(
		Throwable $e,
		string $queueName,
		string $exchangeName='',
		?\Kooriv\MessageBroker\Enum\ExchangeType $exchangeType=null,
		array $routing_keys=[]
	)
	{
		$this
			->getAMQP()
			->publisher()
			->dispatch(
				message: $this->getBody(),
				properties: $this->get_properties()
			)
			->onQueue(
				queue: new FailedJobs(
					queueName: $queueName,
					exchangeName: $exchangeName,
					exchangeType: $exchangeType,
					routing_keys: $routing_keys
				)
			);
	}

	public function getChannel()
	{
		return $this->job->getChannel();
	}

	public function getConsumerTag(): ?string
	{
		return $this->job->getConsumerTag();
	}

	public function getExchange(): ?string
	{
		return $this->job->getExchange();
	}

	public function getRoutingKey(): ?string
	{
		return $this->job->getRoutingKey();
	}

	public function get_properties(): array
	{
		return $this->job->get_properties();
	}

	public function getBody(): string
	{
		return $this->job->getBody();
	}

	public function getAMQP(): AMQP
	{
		return $this->amqp;
	}

	abstract public function payload(MainJob $event);
}