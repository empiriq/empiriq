<?php

namespace Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Responses\UserData\Results;

readonly class ListenKey
{
    public function __construct(
        public string $listenKey,
    ) {
    }
}
