<?php

namespace Empiriq\BinanceContracts\Markets\FuturesCm\Requests\Account;

use Empiriq\BinanceContracts\Markets\FuturesCm\Requests\FuturesCmWsRequestInterface;
use Empiriq\BinanceContracts\Markets\FuturesCm\Common\Permission;
use Empiriq\BinanceContracts\Markets\FuturesCm\Responses\Trading\OrderPlaceResponse;

readonly class OrderStatus implements FuturesCmWsRequestInterface
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
