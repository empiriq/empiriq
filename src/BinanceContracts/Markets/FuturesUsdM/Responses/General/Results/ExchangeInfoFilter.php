<?php

namespace Empiriq\BinanceContracts\Markets\FuturesUsdM\Responses\General\Results;

readonly class ExchangeInfoFilter
{
    /**
     * @param string $filterType
     * @param string|null $maxPrice
     * @param string|null $minPrice
     * @param string|null $tickSize
     * @param string|null $maxQty
     * @param string|null $minQty
     * @param string|null $stepSize
     * @param int|null $limit
     * @param string|null $notional
     * @param string|null $multiplierUp
     * @param string|null $multiplierDown
     * @param string|null $multiplierDecimal
     * @param string|null $positionControlSide
     */
    public function __construct(
        public string $filterType,
        public ?string $maxPrice = null,
        public ?string $minPrice = null,
        public ?string $tickSize = null,
        public ?string $maxQty = null,
        public ?string $minQty = null,
        public ?string $stepSize = null,
        public ?int $limit = null,
        public ?string $notional = null,
        public ?string $multiplierUp = null,
        public ?string $multiplierDown = null,
        public ?string $multiplierDecimal = null,
        public ?string $positionControlSide = null,
    ) {
    }
}
