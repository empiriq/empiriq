<?php

namespace Empiriq\BinanceContracts\Markets\FuturesUsdM\Requests\Trading;

use Empiriq\BinanceContracts\Markets\FuturesUsdM\Common\OrderCancelRestriction;
use Empiriq\BinanceContracts\Markets\FuturesUsdM\Common\OrderIdentifierType;

/**
 * Cancel an active order.
 * @link https://developers.binance.com/docs/derivatives/usds-margined-futures/trade/websocket-api/Cancel-Order
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
