<?php

namespace Empiriq\BinanceContracts\Spot\Spot\Responses\UserData\Results;

readonly class ListenKey
{
    public function __construct(
        public string $listenKey,
    ) {
    }
}
