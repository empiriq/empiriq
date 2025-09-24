<?php

namespace Empiriq\BinanceTradeBundle\Common\Exceptions\Network;

use Empiriq\BinanceTradeBundle\Common\Exceptions\RuntimeException;
use React\Promise\Timer\TimeoutException as ReactTimeoutException;

class TimeoutException extends RuntimeException
{
    public function __construct(string $message = "", int $code = 0, ?ReactTimeoutException $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
