<?php

namespace Empiriq\BinanceContracts\Markets\FuturesCm\Responses\UserData\Results;

readonly class ListenKey
{
    public function __construct(
        public string $listenKey,
    ) {
    }
}
