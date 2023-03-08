<?php 

namespace Kooriv\MessageBroker\RabbitMQ\Enum;

enum Connections: string
{
	case STREAM = 'stream';
	case SSL = 'ssl';
	case SOCKET = 'socket';
}