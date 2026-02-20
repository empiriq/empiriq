<?php

namespace Empiriq\BinanceContracts\Markets\FuturesUsdM\Responses\General\Results;

readonly class ExchangeInfoResult
{
    /**
     * @param string $timezone
     * @param int $serverTime
     * @param string $futuresType
     * @param ExchangeInfoRateLimit[] $rateLimits
     * @param ExchangeInfoFilter[] $exchangeFilters
     * @param ExchangeInfoAsset[] $assets
     * @param ExchangeInfoSymbol[] $symbols
     */
    public function __construct(
        public string $timezone,
        public int $serverTime,
        public string $futuresType,
        public array $rateLimits,
        public array $exchangeFilters,
        public array $assets,
        public array $symbols,
    ) {
    }
}
