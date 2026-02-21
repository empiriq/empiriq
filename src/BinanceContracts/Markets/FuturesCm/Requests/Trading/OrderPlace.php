<?php

namespace Empiriq\BinanceContracts\Markets\FuturesCm\Requests\Trading;

use Empiriq\BinanceContracts\Markets\FuturesCm\Common\OrderNewResponseType;
use Empiriq\BinanceContracts\Markets\FuturesCm\Common\OrderPreventionMode;
use Empiriq\BinanceContracts\Markets\FuturesCm\Common\OrderSide;
use Empiriq\BinanceContracts\Markets\FuturesCm\Common\OrderType;
use Empiriq\BinanceContracts\Markets\FuturesCm\Requests\FuturesCmWsRequestInterface;
use Empiriq\BinanceContracts\Markets\FuturesCm\Common\Permission;
use Empiriq\BinanceContracts\Markets\FuturesCm\Responses\Trading\OrderPlaceResponse;

/**
 * Place new order (TRADE)
 *
 * This adds 1 order to the EXCHANGE_MAX_ORDERS filter and the MAX_NUM_ORDERS filter.
 * @link https://github.com/binance/binance-spot-api-docs/blob/master/web-socket-api.md#place-new-order-trade
 */
readonly abstract class OrderPlace implements FuturesCmWsRequestInterface
{
    public function __construct(
        public string $symbol,
        public OrderSide $side,
        public OrderType $type,
        public ?string $newClientOrderId,
        public ?int $strategyId,
        public ?int $strategyType,
        public ?OrderPreventionMode $selfTradePreventionMode,
        public ?OrderNewResponseType $newOrderRespType,
    ) {
    }

    public function wsMethod(): string
    {
        return 'order.place';
    }

    public function permission(): Permission
    {
        return Permission::TRADE;
    }

    public function responseType(): string
    {
        return OrderPlaceResponse::class;
    }
}
