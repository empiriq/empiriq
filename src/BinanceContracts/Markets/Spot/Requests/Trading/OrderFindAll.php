<?php

namespace Empiriq\BinanceContracts\Markets\Spot\Requests\Trading;

use Empiriq\BinanceContracts\Markets\Spot\Requests\SpotWsRequestInterface;
use Empiriq\BinanceContracts\Markets\Spot\Common\Permission;
use Empiriq\BinanceContracts\Markets\Spot\Responses\Trading\OrderFindAllResponse;

readonly class OrderFindAll implements SpotWsRequestInterface
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
