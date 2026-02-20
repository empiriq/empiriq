<?php

namespace Empiriq\BinanceContracts\Markets\Spot\Responses\UserData\Results;

readonly class ListenKey
{
    public function __construct(
        public string $listenKey,
    ) {
    }
}
