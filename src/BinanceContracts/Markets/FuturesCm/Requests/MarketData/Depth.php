<?php

namespace Empiriq\BinanceContracts\Markets\FuturesCm\Requests\MarketData;

use Empiriq\BinanceContracts\Markets\FuturesCm\Requests\FuturesCmWsRequestInterface;
use Empiriq\BinanceContracts\Markets\FuturesCm\Common\Permission;
use Empiriq\BinanceContracts\Markets\FuturesCm\Responses\MarketData\DepthResponse;

readonly class Depth implements FuturesCmWsRequestInterface
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
