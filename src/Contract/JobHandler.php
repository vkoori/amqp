<?php 

namespace Kooriv\MessageBroker\Contract;

use PhpAmqpLib\Message\AMQPMessage;

interface JobHandler
{
	public function __construct(AMQPMessage $message);

	public function getChannel();

	public function getConsumerTag(): ?string;

	public function getExchange(): ?string;

	public function getRoutingKey(): ?string;

	public function get_properties(): array;

	public function getBody(): string;

	public function ack(): void;
}