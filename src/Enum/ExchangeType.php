<?php 

namespace Kooriv\MessageBroker\Enum;

enum ExchangeType
{
	case DIRECT;
	case FANOUT;
	case TOPIC;
	case HEADERS;
}