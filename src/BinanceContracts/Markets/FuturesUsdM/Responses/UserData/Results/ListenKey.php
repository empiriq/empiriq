<?php

namespace Empiriq\BinanceContracts\Markets\FuturesUsdM\Responses\UserData\Results;

readonly class ListenKey
{
    public function __construct(
        public string $listenKey,
    ) {
    }
}
