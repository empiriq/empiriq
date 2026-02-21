<?php

namespace Empiriq\BinanceRealBundle\DependencyInjection\Compiler;

use Empiriq\BinanceRealBundle\Markets\FuturesUm\Streams\TradeStream as FuturesUsdMTradeStream;
use Empiriq\BinanceRealBundle\Markets\FuturesUm\Streams\UserDataStream as FuturesUsdMUserDataStream;
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
    public const SUBSCRIPTIONS_PARAMETER = 'empiriq.event.subscriptions';

    public function __construct(
        private readonly EventDiscovery $event
    ) {
    }

    #[\Override]
    public function process(ContainerBuilder $container): void
    {
        /** @var array<string, class-string> $mapping */
        $mapping = [
            'binance.futures_usd.market.trade' => FuturesUsdMTradeStream::class,
            'binance.futures_usd.user.account_update' => FuturesUsdMUserDataStream::class,
            'binance.futures_usd.user.order_trade_update' => FuturesUsdMUserDataStream::class,
            'binance.futures_usd.user.margin_call' => FuturesUsdMUserDataStream::class,
            'binance.futures_usd.user.trade_lite' => FuturesUsdMUserDataStream::class,
        ];
        $events = $this->event->discover($container);
        $container->setParameter(self::SUBSCRIPTIONS_PARAMETER, $events);
        foreach ($events as $eventName) {
            $result = $this->parse($mapping, $eventName);
            if ($result) {
                [$serviceId, $def] = $result;
                $container->setDefinition(
                    $serviceId,
                    $def
                )->addTag(self::TAG_FUTURES_USDM);
            }
        }
    }

    /**
     * @param array<string, class-string> $mapping
     * @return array{string, Definition}|null
     */
    private function parse(array $mapping, string $eventName): ?array
    {
        foreach ($mapping as $key => $class) {
            $path = parse_url($eventName, PHP_URL_PATH);
            if (!is_string($path) || $path !== $key) {
                continue;
            }
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

            return [$class, $def];
        }

        return null;
    }
}
