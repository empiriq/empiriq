<?php

namespace Empiriq\BinanceContracts\Markets\FuturesUm\Responses\General\Results;

readonly class ExchangeInfoSymbol
{
    /**
     * @param string $symbol
     * @param string $pair
     * @param string $contractType
     * @param int $deliveryDate
     * @param int $onboardDate
     * @param string $status
     * @param string $maintMarginPercent
     * @param string $requiredMarginPercent
     * @param string $baseAsset
     * @param string $quoteAsset
     * @param string $marginAsset
     * @param int $pricePrecision
     * @param int $quantityPrecision
     * @param int $baseAssetPrecision
     * @param int $quotePrecision
     * @param string $underlyingType
     * @param string[] $underlyingSubType
     * @param string $triggerProtect
     * @param string $liquidationFee
     * @param string $marketTakeBound
     * @param int $maxMoveOrderLimit
     * @param ExchangeInfoFilter[] $filters
     * @param string[] $orderTypes
     * @param string[] $timeInForce
     * @param string[] $permissionSets
     */
    public function __construct(
        public string $symbol,
        public string $pair,
        public string $contractType,
        public int $deliveryDate,
        public int $onboardDate,
        public string $status,
        public string $maintMarginPercent,
        public string $requiredMarginPercent,
        public string $baseAsset,
        public string $quoteAsset,
        public string $marginAsset,
        public int $pricePrecision,
        public int $quantityPrecision,
        public int $baseAssetPrecision,
        public int $quotePrecision,
        public string $underlyingType,
        public array $underlyingSubType,
        public string $triggerProtect,
        public string $liquidationFee,
        public string $marketTakeBound,
        public int $maxMoveOrderLimit,
        public array $filters,
        public array $orderTypes,
        public array $timeInForce,
        public array $permissionSets,
    ) {
    }
}
