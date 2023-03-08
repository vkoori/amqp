<?php 

namespace Kooriv\MessageBroker\Contract;

use Kooriv\MessageBroker\Contract\Publisher;
use Kooriv\MessageBroker\Contract\Consumer;
use Kooriv\MessageBroker\Contract\JobHandler;
use Kooriv\MessageBroker\Enum\ExchangeType;

interface AMQP
{
	public function publisher(): Publisher;

	public function consumer(): Consumer;

	public function job($message): JobHandler;

	public function exchangeType(ExchangeType $type): string;
}