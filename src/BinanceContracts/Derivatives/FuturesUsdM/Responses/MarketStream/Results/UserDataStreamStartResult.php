<?php

namespace Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Responses\MarketStream\Results;

class UserDataStreamStartResult
{
    /**
     * @param string $listenKey
     */
    public function __construct(
        public string $listenKey,
    ) {
    }
}
