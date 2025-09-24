<?php

namespace Empiriq\BinanceContracts\Spot\Spot\Requests\Trading;

use Empiriq\BinanceContracts\Spot\Spot\Common\OrderCancelRestriction;
use Empiriq\BinanceContracts\Spot\Spot\Common\OrderIdentifierType;

readonly class OrderListPlaceOco
{
    public ?int $orderId;

    public ?string $origClientOrderId;

    public function __construct(
        public string $symbol,
        int|string $identifier,
        OrderIdentifierType $identifierType,
        public float $newQty,
        public ?string $newClientOrderId,
        public ?OrderCancelRestriction $cancelRestrictions,
    ) {
        $this->orderId = $identifierType === OrderIdentifierType::EXCHANGE ? $identifier : null;
        $this->origClientOrderId = $identifierType === OrderIdentifierType::CLIENT ? $identifier : null;
    }
}
