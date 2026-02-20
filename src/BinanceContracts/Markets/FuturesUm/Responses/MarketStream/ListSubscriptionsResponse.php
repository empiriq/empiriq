<?php

namespace Empiriq\BinanceContracts\Markets\FuturesUm\Responses\MarketStream;

readonly class ListSubscriptionsResponse
{
    /**
     * @param string $id
     * @param array $result
     */
    public function __construct(
        public string $id,
        public array $result,
    ) {
    }
}
