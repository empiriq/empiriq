<?php

namespace Empiriq\BinanceTradeBundle\DependencyInjection\Compiler;

use Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\Streams\TradeStream as FuturesUsdMTradeStream;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\Streams\UserDataStream as FuturesUsdMUserDataStream;
use Empiriq\SymfonyEventDiscovery\EventDiscovery;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\HttpFoundation\HeaderUtils;

final class StreamBuildPass implements CompilerPassInterface
{
    public const TAG_FUTURES_USDM = 'empiriq.binance.futures_usdm.stream';
    public const TAG_FUTURES_COINM = 'empiriq.binance.futures_coinm.stream';
    public const TAG_SPOT = 'empiriq.binance.spot.stream';

    public function __construct(
        private readonly EventDiscovery $event
    ) {
    }

    #[\Override]
    public function process(ContainerBuilder $container): void
    {
        $mapping = [
            'binance.futures_usd.market.trade' => FuturesUsdMTradeStream::class,
            'binance.futures_usd.user.account_update' => FuturesUsdMUserDataStream::class,
            'binance.futures_usd.user.order_trade_update' => FuturesUsdMUserDataStream::class,
            'binance.futures_usd.user.margin_call' => FuturesUsdMUserDataStream::class,
            'binance.futures_usd.user.trade_lite' => FuturesUsdMUserDataStream::class,
        ];
        $events = $this->event->discover($container);
        foreach ($events as $eventName) {
            $def = $this->parse($mapping, $eventName);
            if ($def) {
                $container->setDefinition(
                    $def->getClass(),
                    $def
                )->addTag(self::TAG_FUTURES_USDM);
            }
        }
    }

    private function parse(array $mapping, string $eventName): ?Definition
    {
        foreach ($mapping as $key => $class) {
            if (parse_url($eventName, PHP_URL_PATH) === $key) {
                $def = new Definition($class);
                $rc = new \ReflectionClass($class);
                $ctor = $rc->getConstructor();
                $params = $ctor?->getParameters() ?? [];
                $query = parse_url($eventName, PHP_URL_QUERY);
                if (is_string($query) && $query !== '') {
                    $params2 = HeaderUtils::parseQuery($query);
                    /* @var \ReflectionParameter $argument */
                    foreach ($params as $i => $argument) {
                        $def->setArgument($i, $params2[$argument->name] ?? null);
                    }
                }

                return $def;
            }
        }

        return null;
    }
}
