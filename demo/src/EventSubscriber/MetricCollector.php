<?php

namespace App\EventSubscriber;

use Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Events\Market\TradeEvent;
use Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Events\User\AccountUpdateEvent;
use Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Events\User\OrderTradeUpdateEvent;
use Prometheus\CollectorRegistry;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class MetricCollector implements EventSubscriberInterface
{
    public function __construct(
        private CollectorRegistry $registry,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'ticker.tick.1s' => 'handleTicker',
            TradeEvent::class => 'handleTrade',
            OrderTradeUpdateEvent::class => 'handleOrderTradeUpdate',
            AccountUpdateEvent::class => 'handleAccountUpdateEvent',
        ];
    }

    public function handleTicker(object $event): void
    {
        var_dump('handleTicker');
        var_dump('handleTicker');
        var_dump('handleTicker');
        var_dump('handleTicker');
    }

    public function handleTrade(TradeEvent $event): void
    {
        $this->registry->getOrRegisterCounter('market_events', 'trade_count', 'help')->inc();
    }

    public function handleOrderTradeUpdate(OrderTradeUpdateEvent $event): void
    {
        $this->registry->getOrRegisterCounter('user_events', 'order_trade_update_count', 'help')->inc();
    }

    public function handleAccountUpdateEvent(AccountUpdateEvent $event): void
    {
        $this->registry->getOrRegisterCounter('user_events', 'account_update_count', 'help')->inc();
    }
}
