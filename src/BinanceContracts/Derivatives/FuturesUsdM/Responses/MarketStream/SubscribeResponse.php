<?php

namespace Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Responses\MarketStream;

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
