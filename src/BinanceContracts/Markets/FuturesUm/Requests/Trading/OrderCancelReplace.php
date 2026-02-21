<?php

namespace Empiriq\BinanceContracts\Markets\FuturesUm\Requests\Trading;

use Empiriq\BinanceContracts\Markets\FuturesUm\Common\OrderCancelReplaceMode;
use Empiriq\BinanceContracts\Markets\FuturesUm\Common\OrderIdentifierType;
use Empiriq\BinanceContracts\Markets\FuturesUm\Common\OrderSide;
use Empiriq\BinanceContracts\Markets\FuturesUm\Common\OrderType;
use Empiriq\BinanceContracts\Markets\FuturesUm\Requests\FuturesUmWsRequestInterface;
use Empiriq\BinanceContracts\Markets\FuturesUm\Common\Permission;
use Empiriq\BinanceContracts\Markets\FuturesUm\Responses\Trading\OrderCancelResponse;

readonly class OrderCancelReplace implements FuturesUmWsRequestInterface
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

    public function wsMethod(): string
    {
        return 'order.cancelReplace';
    }

    public function permission(): Permission
    {
        return Permission::TRADE;
    }

    public function responseType(): string
    {
        return OrderCancelResponse::class;
    }
}
