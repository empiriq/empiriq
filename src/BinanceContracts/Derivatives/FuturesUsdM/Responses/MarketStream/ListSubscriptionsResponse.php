<?php

namespace Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Responses\MarketStream;

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
