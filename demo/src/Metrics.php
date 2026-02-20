<?php

namespace App;

use Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Events\Market\TradeEvent;
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
            'binance.futures_usd.market.trade?symbol=btcusdt' => 'trade',
            'ticker.interval?second=2' => 'push',
        ];
    }

    public function trade(TradeEvent $event): void
    {
        $this->registry->getOrRegisterCounter('market_events', 'trade_count', 'Trade count')->inc();
    }

    public function push(): void
    {
        var_dump(777);
        $this->gateway->push($this->registry, 'trade_workers');
    }
}
