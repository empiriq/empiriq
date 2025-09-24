<?php

namespace Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Requests\Trading;

use Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Common\OrderCancelRestriction;
use Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Common\OrderIdentifierType;

/**
 * Cancel order (TRADE)
 *
 * Cancel an active order.
 * @link https://github.com/binance/binance-spot-api-docs/blob/master/web-socket-api.md#cancel-order-trade
 */
readonly class OrderCancel
{
    public int $orderId;

    public string $origClientOrderId;

    public function __construct(
        public string $symbol,
        int|string $identifier,
        OrderIdentifierType $identifierType,
        public ?string $newClientOrderId,
        public ?OrderCancelRestriction $cancelRestrictions,
    ) {
        $this->orderId = $identifierType === OrderIdentifierType::EXCHANGE ? $identifier : null;
        $this->origClientOrderId = $identifierType === OrderIdentifierType::CLIENT ? $identifier : null;
    }
}
