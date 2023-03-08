<?php 

namespace Kooriv\MessageBroker\RabbitMQ\Lib;

use Kooriv\MessageBroker\RabbitMQ\Contract\Connection as ContractConnection;
use Kooriv\MessageBroker\Exception\ConnectionException;
use Kooriv\MessageBroker\RabbitMQ\Enum\Connections;
use PhpAmqpLib\Connection\{
	AMQPStreamConnection,
	AMQPSSLConnection,
	AMQPSocketConnection,
	AbstractConnection
};

class Connection implements ContractConnection
{
	public function create(): AbstractConnection
	{
		return $this->getConnection()::create_connection(
			hosts: $this->getHosts(),
			options: $this->getSSL()
		);
	}

	// public function close(AbstractConnection $connection)
	// {
	// 	$connection->close();
	// }

	private function getConnection()
	{
		$connectionType = config('amqp.rabbitMQ.connection_type');

		return match (true) {
			Connections::SOCKET == $connectionType => AMQPStreamConnection::class,
			Connections::SSL == $connectionType => AMQPSSLConnection::class,
			Connections::STREAM == $connectionType => AMQPSocketConnection::class,
			default => throw new ConnectionException("The connection type is not accepted"),
		};
	}

	private function getHosts()
	{
		return config('amqp.rabbitMQ.hosts');
	}

	private function getSSL()
	{
		return config('amqp.rabbitMQ.ssl_options');
	}
}