<?php

namespace Empiriq\BinanceContracts\Spot\Spot\Requests\Trading;

use Empiriq\BinanceContracts\Spot\Spot\Common\OrderCancelRestriction;
use Empiriq\BinanceContracts\Spot\Spot\Common\OrderIdentifierType;

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
