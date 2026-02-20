<?php

namespace Empiriq\BinanceContracts\Markets\FuturesCoinM\Responses\UserData\Results;

readonly class ListenKey
{
    public function __construct(
        public string $listenKey,
    ) {
    }
}
