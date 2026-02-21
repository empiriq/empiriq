<?php

namespace Empiriq\BinanceRealBundle\Common\Exceptions\Network;

use Empiriq\BinanceRealBundle\Common\Exceptions\RuntimeException;
use React\Promise\Timer\TimeoutException as ReactTimeoutException;

class TimeoutException extends RuntimeException
{
    public function __construct(string $message = "", int $code = 0, ?ReactTimeoutException $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
