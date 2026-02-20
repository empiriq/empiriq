<?php

namespace Empiriq\BinanceContracts\Markets\FuturesUm\Responses\MarketStream\Results;

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
