<?php

namespace App\EventSubscriber;

use Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Events\Market\TradeEvent;
use Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Events\User\AccountUpdateEvent;
use Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Events\User\OrderTradeUpdateEvent;

class Metrics
{
    public static function getSubscribedEvents(): array
    {
        return [
            TradeEvent::class => 'handleTrade',
            OrderTradeUpdateEvent::class => 'handleOrderTradeUpdateTrade',
            AccountUpdateEvent::class => 'handleAccountUpdateEvent',
        ];
    }

    public function handleTrade(TradeEvent $event): void
    {
        var_dump($event);
    }

    public function handleOrderTradeUpdateTrade(OrderTradeUpdateEvent $event): void
    {
        var_dump($event);
    }

    public function handleAccountUpdateEvent(AccountUpdateEvent $event): void
    {
        var_dump($event);
    }
}
