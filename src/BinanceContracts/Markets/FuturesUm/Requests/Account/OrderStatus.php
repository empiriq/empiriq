<?php

namespace Empiriq\BinanceContracts\Markets\FuturesUm\Requests\Account;

use Empiriq\BinanceContracts\Markets\FuturesUm\Requests\FuturesUmWsRequestInterface;
use Empiriq\BinanceContracts\Markets\FuturesUm\Common\Permission;
use Empiriq\BinanceContracts\Markets\FuturesUm\Responses\Trading\OrderPlaceResponse;

readonly class OrderStatus implements FuturesUmWsRequestInterface
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
