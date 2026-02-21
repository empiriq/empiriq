<?php

namespace Empiriq\BinanceContracts\Markets\FuturesUm\Requests\Trading;

use Empiriq\BinanceContracts\Markets\FuturesUm\Requests\FuturesUmWsRequestInterface;
use Empiriq\BinanceContracts\Markets\FuturesUm\Common\Permission;
use Empiriq\BinanceContracts\Markets\FuturesUm\Responses\Trading\OrderCancelResponse;

/**
 * Cancel all open orders on a symbol. This includes orders that are part of an order list.
 * @link https://github.com/binance/binance-spot-api-docs/blob/master/web-socket-api.md#cancel-open-orders-trade
 */
readonly class OrderCancelAll implements FuturesUmWsRequestInterface
{
    public function __construct(
        public string $symbol,
    ) {
    }

    public function wsMethod(): string
    {
        return 'openOrders.cancelAll';
    }

    public function permission(): Permission
    {
        return Permission::TRADE;
    }

    public function responseType(): string
    {
        return OrderCancelResponse::class;
    }
}
