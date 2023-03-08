<?php 

namespace Kooriv\MessageBroker\Contract;

use Kooriv\MessageBroker\Contract\AMQP;

interface MainJob
{
	public function getChannel();

	public function getConsumerTag(): ?string;

	public function getExchange(): ?string;

	public function getRoutingKey(): ?string;

	public function get_properties(): array;

	public function getBody(): string;

	public function getAMQP(): AMQP;
}