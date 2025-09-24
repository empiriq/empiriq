<?php

namespace Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Responses\UserData\Results;

readonly class ListenKey
{
    public function __construct(
        public string $listenKey,
    ) {
    }
}
