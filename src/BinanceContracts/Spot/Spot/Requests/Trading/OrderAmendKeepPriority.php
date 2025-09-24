<?php

namespace Empiriq\BinanceContracts\Spot\Spot\Requests\Trading;

use Empiriq\BinanceContracts\Spot\Spot\Common\OrderCancelRestriction;
use Empiriq\BinanceContracts\Spot\Spot\Common\OrderIdentifierType;

/**
 * Order Amend Keep Priority (TRADE)
 *
 * Order Amend Keep Priority request is used to modify (amend) an existing order without losing order book priority.
 * The following order modifications are allowed:
 *   - reduce the quantity of the order
 *
 * @link https://github.com/binance/binance-spot-api-docs/blob/master/web-socket-api.md#order-amend-keep-priority-trade
 * @link https://github.com/binance/binance-spot-api-docs/blob/master/faqs/order_amend_keep_priority.md
 */
readonly class OrderAmendKeepPriority
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
