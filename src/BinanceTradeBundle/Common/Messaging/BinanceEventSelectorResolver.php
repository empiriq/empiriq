<?php

namespace Empiriq\BinanceTradeBundle\Common\Messaging;

use Empiriq\BinanceContracts\Markets\FuturesCm\Events\Market\DepthEvent as FuturesCmDepthEvent;
use Empiriq\BinanceContracts\Markets\FuturesCm\Events\Market\TradeEvent as FuturesCmTradeEvent;
use Empiriq\BinanceContracts\Markets\FuturesCm\Events\User\BalanceUpdateEvent as FuturesCmBalanceUpdateEvent;
use Empiriq\BinanceContracts\Markets\FuturesCm\Events\User\ExecutionReportEvent as FuturesCmExecutionReportEvent;
use Empiriq\BinanceContracts\Markets\FuturesCm\Events\User\ExternalLockUpdateEvent as FuturesCmExternalLockUpdateEvent;
use Empiriq\BinanceContracts\Markets\FuturesCm\Events\User\OutboundAccountPositionEvent as FuturesCmOapEvent;
use Empiriq\BinanceContracts\Markets\FuturesUm\Events\Market\DepthEvent as FuturesUmDepthEvent;
use Empiriq\BinanceContracts\Markets\FuturesUm\Events\Market\TradeEvent as FuturesUmTradeEvent;
use Empiriq\BinanceContracts\Markets\FuturesUm\Events\User\AccountUpdateEvent as FuturesUmAccountUpdateEvent;
use Empiriq\BinanceContracts\Markets\FuturesUm\Events\User\MarginCallEvent as FuturesUmMarginCallEvent;
use Empiriq\BinanceContracts\Markets\FuturesUm\Events\User\OrderTradeUpdateEvent as FuturesUmOrderTradeUpdateEvent;
use Empiriq\BinanceContracts\Markets\FuturesUm\Events\User\TradeLiteEvent as FuturesUmTradeLiteEvent;
use Empiriq\BinanceContracts\Markets\Spot\Events\Market\DepthEvent as SpotDepthEvent;
use Empiriq\BinanceContracts\Markets\Spot\Events\Market\TradeEvent as SpotTradeEvent;
use Empiriq\BinanceContracts\Markets\Spot\Events\User\BalanceUpdateEvent as SpotBalanceUpdateEvent;
use Empiriq\BinanceContracts\Markets\Spot\Events\User\ExecutionReportEvent as SpotExecutionReportEvent;
use Empiriq\BinanceContracts\Markets\Spot\Events\User\ExternalLockUpdateEvent as SpotExternalLockUpdateEvent;
use Empiriq\BinanceContracts\Markets\Spot\Events\User\OutboundAccountPositionEvent as SpotOutboundAccountPositionEvent;
use Empiriq\Contracts\Messaging\EventSelectorResolverInterface;

/**
 * Resolves Binance domain events to selector-aware event names.
 * Public API for bundle integrations.
 *
 * @api
 */
final class BinanceEventSelectorResolver implements EventSelectorResolverInterface
{
    private const PATHS = [
        SpotTradeEvent::class => 'binance.spot.market.trade',
        SpotDepthEvent::class => 'binance.spot.market.depth',
        SpotBalanceUpdateEvent::class => 'binance.spot.user.balance_update',
        SpotOutboundAccountPositionEvent::class => 'binance.spot.user.outbound_account_position',
        SpotExecutionReportEvent::class => 'binance.spot.user.execution_report',
        SpotExternalLockUpdateEvent::class => 'binance.spot.user.external_lock_update',

        FuturesUmTradeEvent::class => 'binance.futures_usd.market.trade',
        FuturesUmDepthEvent::class => 'binance.futures_usd.market.depth',
        FuturesUmAccountUpdateEvent::class => 'binance.futures_usd.user.account_update',
        FuturesUmOrderTradeUpdateEvent::class => 'binance.futures_usd.user.order_trade_update',
        FuturesUmMarginCallEvent::class => 'binance.futures_usd.user.margin_call',
        FuturesUmTradeLiteEvent::class => 'binance.futures_usd.user.trade_lite',

        FuturesCmTradeEvent::class => 'binance.futures_coin.market.trade',
        FuturesCmDepthEvent::class => 'binance.futures_coin.market.depth',
        FuturesCmBalanceUpdateEvent::class => 'binance.futures_coin.user.balance_update',
        FuturesCmOapEvent::class => 'binance.futures_coin.user.outbound_account_position',
        FuturesCmExecutionReportEvent::class => 'binance.futures_coin.user.execution_report',
        FuturesCmExternalLockUpdateEvent::class => 'binance.futures_coin.user.external_lock_update',
    ];

    #[\Override]
    public function resolve(object $event): ?array
    {
        $class = $event::class;
        $path = self::PATHS[$class] ?? null;
        if ($path === null) {
            return null;
        }

        $selectors = [];
        if (isset($event->symbol) && is_string($event->symbol) && $event->symbol !== '') {
            $selectors['symbol'] = strtolower($event->symbol);
        }

        return [
            'path' => $path,
            'selectors' => $selectors,
        ];
    }
}
