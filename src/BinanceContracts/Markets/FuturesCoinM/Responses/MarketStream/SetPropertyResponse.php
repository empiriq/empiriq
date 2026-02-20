<?php

namespace Empiriq\BinanceContracts\Markets\FuturesCoinM\Responses\MarketStream;

readonly class SetPropertyResponse
{
    /**
     * @param string $id
     * @param null $result
     */
    public function __construct(
        public string $id,
        public null $result,
    ) {
    }
}
