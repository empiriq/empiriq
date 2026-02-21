<?php

namespace Empiriq\BinanceContracts\Markets\FuturesUm\Requests\Trading;

use Empiriq\BinanceContracts\Markets\FuturesUm\Common\OrderNewResponseType;
use Empiriq\BinanceContracts\Markets\FuturesUm\Common\OrderPreventionMode;
use Empiriq\BinanceContracts\Markets\FuturesUm\Common\OrderSide;
use Empiriq\BinanceContracts\Markets\FuturesUm\Common\OrderType;
use Empiriq\BinanceContracts\Markets\FuturesUm\Requests\FuturesUmWsRequestInterface;
use Empiriq\BinanceContracts\Markets\FuturesUm\Common\Permission;
use Empiriq\BinanceContracts\Markets\FuturesUm\Responses\Trading\OrderPlaceResponse;

/**
 * Base DTO for USD-M Futures `order.place` request payloads.
 *
 * We pin `newOrderRespType` to RESULT by default to always decode the full
 * typed order response payload.
 *
 * @see https://developers.binance.com/docs/derivatives/usds-margined-futures/trade/websocket-api/New-Order
 */
readonly abstract class OrderPlace implements FuturesUmWsRequestInterface
{
    public function __construct(
        public string $symbol,
        public OrderSide $side,
        public OrderType $type,
        public ?string $newClientOrderId,
        public ?int $strategyId,
        public ?int $strategyType,
        public ?OrderPreventionMode $selfTradePreventionMode,
        public OrderNewResponseType $newOrderRespType = OrderNewResponseType::RESULT,
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
