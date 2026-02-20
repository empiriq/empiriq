<?php

namespace Empiriq\BinanceContracts\Markets\FuturesUm\Responses\UserData\Results;

readonly class ListenKey
{
    public function __construct(
        public string $listenKey,
    ) {
    }
}
