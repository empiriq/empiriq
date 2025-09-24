<?php

namespace Empiriq\BinanceContracts\Spot\Spot\Responses\MarketStream;

readonly class SubscribeResponse
{
    /**
     * @param string $id
     * @param null $result
     */
    public function __construct(
        public string $id,
        public null $result,
    ) {
    }
}
