<?php

namespace Empiriq\BinanceContracts\Markets\Spot\Requests\Trading;

use Empiriq\BinanceContracts\Markets\Spot\Common\OrderCancelRestriction;
use Empiriq\BinanceContracts\Markets\Spot\Common\OrderIdentifierType;
use Empiriq\BinanceContracts\Markets\Spot\Requests\SpotWsRequestInterface;
use Empiriq\BinanceContracts\Markets\Spot\Common\Permission;
use Empiriq\BinanceContracts\Markets\Spot\Responses\Trading\OrderPlaceResponse;

readonly class OrderListPlaceOco implements SpotWsRequestInterface
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

    public function wsMethod(): string
    {
        return 'orderList.place.oco';
    }

    public function permission(): Permission
    {
        return Permission::TRADE;
    }

    public function responseType(): string
    {
        return OrderPlaceResponse::class;
    }
}
