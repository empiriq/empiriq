<?php

namespace Empiriq\BinanceContracts\Markets\FuturesUm\Responses\MarketStream;

readonly class GetPropertyResponse
{
    /**
     * @param string $id
     * @param mixed $result
     */
    public function __construct(
        public string $id,
        public mixed $result,
    ) {
    }
}
