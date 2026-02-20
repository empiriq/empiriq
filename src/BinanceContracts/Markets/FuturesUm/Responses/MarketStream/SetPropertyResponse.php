<?php

namespace Empiriq\BinanceContracts\Markets\FuturesUm\Responses\MarketStream;

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
