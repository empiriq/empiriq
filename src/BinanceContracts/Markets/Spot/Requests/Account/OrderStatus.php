<?php

namespace Empiriq\BinanceContracts\Markets\Spot\Requests\Account;

use Empiriq\BinanceContracts\Markets\Spot\Requests\SpotWsRequestInterface;
use Empiriq\BinanceContracts\Markets\Spot\Common\Permission;
use Empiriq\BinanceContracts\Markets\Spot\Responses\Trading\OrderPlaceResponse;

readonly class OrderStatus implements SpotWsRequestInterface
{
    public function wsMethod(): string
    {
        return 'order.status';
    }

    public function permission(): Permission
    {
        return Permission::USER_DATA;
    }

    public function responseType(): string
    {
        return OrderPlaceResponse::class;
    }
}
