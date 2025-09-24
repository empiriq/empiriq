<?php

namespace Empiriq\BinanceTradeBundle\Common\Exceptions\Binance;

use Empiriq\BinanceTradeBundle\Common\Exceptions\RuntimeException;
use Empiriq\BinanceContracts\Spot\Spot\Common\RateLimit;

//todo use ErrorCode
class BinanceException extends RuntimeException
{
    /**
     * @var string
     */
    private string $request;

    /**
     * @var array
     */
    private array $response;

    /**
     * @var RateLimit[]
     */
    private array $rateLimits;

    /**
     * @param string $message
     * @param int $code
     * @param string $request
     * @param array $response
     * @param RateLimit[] $rateLimits
     */
    public function __construct(
        string $message = "",
        int $code = 0,
        string $request = '',
        array $response = [],
        array $rateLimits = []
    ) {
        parent::__construct($message, $code);
        $this->request = $request;
        $this->response = $response;
        $this->rateLimits = $rateLimits;
    }

    /**
     * @return string
     */
    public function getRequest(): string
    {
        return $this->request;
    }

    /**
     * @return array
     */
    public function getResponse(): array
    {
        return $this->response;
    }

    /**
     * @return RateLimit[]
     */
    public function getRateLimit(): array
    {
        return $this->rateLimits;
    }

    //todo __toString()
}
