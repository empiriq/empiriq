<?php

namespace Empiriq\BinanceContracts\Markets\Spot\Requests\Trading;

use Empiriq\BinanceContracts\Markets\Spot\Common\OrderCancelReplaceMode;
use Empiriq\BinanceContracts\Markets\Spot\Common\OrderIdentifierType;
use Empiriq\BinanceContracts\Markets\Spot\Common\OrderSide;
use Empiriq\BinanceContracts\Markets\Spot\Common\OrderType;
use Empiriq\BinanceContracts\Markets\Spot\Requests\SpotWsRequestInterface;
use Empiriq\BinanceContracts\Markets\Spot\Common\Permission;
use Empiriq\BinanceContracts\Markets\Spot\Responses\Trading\OrderCancelResponse;

readonly class OrderCancelReplace implements SpotWsRequestInterface
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
