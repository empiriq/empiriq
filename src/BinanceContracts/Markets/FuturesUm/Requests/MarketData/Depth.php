<?php

namespace Empiriq\BinanceContracts\Markets\FuturesUm\Requests\MarketData;

use Empiriq\BinanceContracts\Markets\FuturesUm\Requests\FuturesUmWsRequestInterface;
use Empiriq\BinanceContracts\Markets\FuturesUm\Common\Permission;
use Empiriq\BinanceContracts\Markets\FuturesUm\Responses\MarketData\DepthResponse;

readonly class Depth implements FuturesUmWsRequestInterface
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
