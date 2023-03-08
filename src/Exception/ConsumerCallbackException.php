<?php

namespace Kooriv\MessageBroker\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException as BaseException;

class ConsumerCallbackException extends BaseException
{
    public function __construct(string $message = '', \Throwable $previous = null, array $headers = [], int $code = 0)
    {
        parent::__construct(statusCode:500, message: $message, previous: $previous, headers: $headers, code: $code);
    }
}
