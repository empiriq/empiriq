<?php

namespace Empiriq\BinanceContracts\Markets\Spot\Requests\Trading;

use Empiriq\BinanceContracts\Markets\Spot\Common\OrderNewResponseType;
use Empiriq\BinanceContracts\Markets\Spot\Common\OrderPreventionMode;
use Empiriq\BinanceContracts\Markets\Spot\Common\OrderSide;
use Empiriq\BinanceContracts\Markets\Spot\Common\OrderType;
use Empiriq\BinanceContracts\Markets\Spot\Requests\SpotWsRequestInterface;
use Empiriq\BinanceContracts\Markets\Spot\Common\Permission;
use Empiriq\BinanceContracts\Markets\Spot\Responses\Trading\OrderPlaceAskResponse;
use Empiriq\BinanceContracts\Markets\Spot\Responses\Trading\OrderPlaceFullResponse;
use Empiriq\BinanceContracts\Markets\Spot\Responses\Trading\OrderPlaceResponse;

/**
 * Place new order (TRADE)
 *
 * This adds 1 order to the EXCHANGE_MAX_ORDERS filter and the MAX_NUM_ORDERS filter.
 * @link https://github.com/binance/binance-spot-api-docs/blob/master/web-socket-api.md#place-new-order-trade
 */
readonly abstract class OrderPlace implements SpotWsRequestInterface
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
        return match ($this->newOrderRespType) {
            OrderNewResponseType::ACK => OrderPlaceAskResponse::class,
            OrderNewResponseType::FULL => OrderPlaceFullResponse::class,
            default => OrderPlaceResponse::class,
        };
    }
}
