<?php 

namespace Kooriv\MessageBroker\Event;

use Kooriv\MessageBroker\Exception\InvalidConsumer;

class Consumers implements \Iterator
{
	/**
	 * @var array<\Kooriv\MessageBroker\Event\PubSub>
	 */
	protected array $pubSub = [];

	private int $position = 0;

	public function rewind(): void {
		$this->position = 0;
	}

	#[\ReturnTypeWillChange]
	public function current() {
		return $this->pubSub[$this->position];
	}

	#[\ReturnTypeWillChange]
	public function key() {
		return $this->position;
	}

	public function next(): void {
		++$this->position;
	}

	public function valid(): bool {
		$valid = isset($this->pubSub[$this->position]);

		if (
			$valid
			&& !$this->pubSub[$this->position] instanceof PubSub
		) {
			throw new InvalidConsumer("Pub/Sub is not valid. The Pub/Sub class must be an instance of ".PubSub::class);
		}

		return $valid;
	}
}