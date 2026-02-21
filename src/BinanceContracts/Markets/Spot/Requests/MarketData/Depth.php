<?php

namespace Empiriq\BinanceContracts\Markets\Spot\Requests\MarketData;

use Empiriq\BinanceContracts\Markets\Spot\Requests\SpotWsRequestInterface;
use Empiriq\BinanceContracts\Markets\Spot\Common\Permission;
use Empiriq\BinanceContracts\Markets\Spot\Responses\MarketData\DepthResponse;

readonly class Depth implements SpotWsRequestInterface
{
    public function __construct(
        public string $symbol,
        public int $limit,
    ) {
    }

    public function wsMethod(): string
    {
        return 'depth';
    }

    public function permission(): Permission
    {
        return Permission::NONE;
    }

    public function responseType(): string
    {
        return DepthResponse::class;
    }
}
