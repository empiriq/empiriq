<?php

namespace Empiriq\BinanceContracts\Markets\FuturesUm\Responses\General\Results;

readonly class ExchangeInfoAsset
{
    /**
     * @param string $asset
     * @param bool $marginAvailable
     * @param string $autoAssetExchange
     */
    public function __construct(
        public string $asset,
        public bool $marginAvailable,
        public string $autoAssetExchange,
    ) {
    }
}
