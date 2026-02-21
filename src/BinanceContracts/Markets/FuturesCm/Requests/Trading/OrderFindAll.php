<?php

namespace Empiriq\BinanceContracts\Markets\FuturesCm\Requests\Trading;

use Empiriq\BinanceContracts\Markets\FuturesCm\Requests\FuturesCmWsRequestInterface;
use Empiriq\BinanceContracts\Markets\FuturesCm\Common\Permission;
use Empiriq\BinanceContracts\Markets\FuturesCm\Responses\Trading\OrderFindAllResponse;

readonly class OrderFindAll implements FuturesCmWsRequestInterface
{
    public function __construct(
        public string $symbol,
        public ?int $startTime = null,
        public ?int $endTime = null,
        public ?int $limit = null,
    ) {
    }

    public function wsMethod(): string
    {
        return 'allOrders';
    }

    public function permission(): Permission
    {
        return Permission::TRADE;
    }

    public function responseType(): string
    {
        return OrderFindAllResponse::class;
    }
}
