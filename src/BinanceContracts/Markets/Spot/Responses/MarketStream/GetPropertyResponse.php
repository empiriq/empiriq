<?php

namespace Empiriq\BinanceContracts\Markets\Spot\Responses\MarketStream;

readonly class GetPropertyResponse
{
    /**
     * @param string $id
     * @param mixed $result
     */
    public function __construct(
        public string $id,
        public mixed $result,
    ) {
    }
}
