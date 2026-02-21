<?php

namespace Empiriq\BinanceContracts\Markets\FuturesUm\Requests\Trading;

use Empiriq\BinanceContracts\Markets\FuturesUm\Requests\FuturesUmWsRequestInterface;
use Empiriq\BinanceContracts\Markets\FuturesUm\Common\Permission;
use Empiriq\BinanceContracts\Markets\FuturesUm\Responses\Trading\OrderFindAllResponse;

readonly class OrderFindAll implements FuturesUmWsRequestInterface
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
