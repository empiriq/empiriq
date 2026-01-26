<?php

namespace App\EventSubscriber;

use Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Events\Market\TradeEvent;
use Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Events\User\AccountUpdateEvent;
use Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Events\User\OrderTradeUpdateEvent;
use Prometheus\CollectorRegistry;
use PrometheusPushGateway\PushGateway;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class Metrics implements EventSubscriberInterface
{
    public function __construct(
        private CollectorRegistry $registry,
        private PushGateway $gateway
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            TradeEvent::class => 'handleTrade',
            OrderTradeUpdateEvent::class => 'handleOrderTradeUpdate',
            AccountUpdateEvent::class => 'handleAccountUpdateEvent',
        ];
    }

    public function handleTrade(TradeEvent $event): void
    {
        $this->registry->getOrRegisterCounter('market_events', 'trade_count', 'help')->inc();
        $this->gateway->push($this->registry, 'trade_workers');
    }

    public function handleOrderTradeUpdate(OrderTradeUpdateEvent $event): void
    {
        $this->registry->getOrRegisterCounter('user_events', 'order_trade_update_count', 'help')->inc();
        $this->gateway->push($this->registry, 'trade_workers');
    }

    public function handleAccountUpdateEvent(AccountUpdateEvent $event): void
    {
        $this->registry->getOrRegisterCounter('user_events', 'account_update_count', 'help')->inc();
        $this->gateway->push($this->registry, 'trade_workers');
    }
}
