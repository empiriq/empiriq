<?php

namespace Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Requests\Trading;

use Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Common\OrderCancelReplaceMode;
use Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Common\OrderIdentifierType;
use Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Common\OrderSide;
use Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Common\OrderType;

readonly class OrderCancelReplace
{
    public ?int $cancelOrderId;

    public ?string $cancelOrigClientOrderId;

    public function __construct(
        public string $symbol,
        int|string $identifier,
        OrderIdentifierType $identifierType,
        public OrderCancelReplaceMode $cancelReplaceMode,
        public ?string $cancelNewClientOrderId,
        public OrderSide $side,
        public OrderType $type,
    ) {
        $this->cancelOrderId = $identifierType === OrderIdentifierType::EXCHANGE ? $identifier : null;
        $this->cancelOrigClientOrderId = $identifierType === OrderIdentifierType::CLIENT ? $identifier : null;
    }
}
