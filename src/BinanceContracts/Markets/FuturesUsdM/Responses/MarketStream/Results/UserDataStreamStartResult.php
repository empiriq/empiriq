<?php

namespace Empiriq\BinanceContracts\Markets\FuturesUsdM\Responses\MarketStream\Results;

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
