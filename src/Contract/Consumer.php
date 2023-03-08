<?php 

namespace Kooriv\MessageBroker\Contract;

interface Consumer
{
	public function handle(): void;
}