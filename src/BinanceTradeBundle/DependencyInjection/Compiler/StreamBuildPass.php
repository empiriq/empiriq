<?php

namespace Empiriq\BinanceTradeBundle\DependencyInjection\Compiler;

use Empiriq\BinanceTradeBundle\Derivatives\FuturesCoinM\Streams\TradeStream as FuturesCoinMTradeStream;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesCoinM\Streams\UserDataStream as FuturesCoinMUserDataStream;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\Streams\TradeStream as FuturesUsdMTradeStream;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\Streams\UserDataStream as FuturesUsdMUserDataStream;
use Empiriq\BinanceTradeBundle\Spot\Spot\Streams\TradeStream as SpotTradeStream;
use Empiriq\BinanceTradeBundle\Spot\Spot\Streams\UserDataStream as SpotUserDataStream;
use Empiriq\SymfonyEventDiscovery\EventDiscovery;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

final class StreamBuildPass implements CompilerPassInterface
{
    public const TAG_FUTURES_USDM = 'empiriq.binance.futures_usdm.stream';
    public const TAG_FUTURES_COINM = 'empiriq.binance.futures_coinm.stream';
    public const TAG_SPOT = 'empiriq.binance.spot.stream';

    /**
     * @var array<int, array{
     *     pattern: non-empty-string,
     *     service_id: string,
     *     class: class-string,
     *     tag: string,
     *     requires_symbols: bool
     * }>
     */
    private const STREAM_MAPPINGS = [
        // Futures USD-M
        [
            'pattern' => '/^binance\.futures_usd\.market\.trade\?symbol=(.+)$/i',
            'service_id' => 'empiriq.binance.futures_usdm.stream.trade',
            'class' => FuturesUsdMTradeStream::class,
            'tag' => self::TAG_FUTURES_USDM,
            'requires_symbols' => true,
        ],
        [
            'pattern' => '/^binance\.futures_usd\.user\.(account_update|order_trade_update|margin_call|trade_lite)$/i',
            'service_id' => 'empiriq.binance.futures_usdm.stream.user_data',
            'class' => FuturesUsdMUserDataStream::class,
            'tag' => self::TAG_FUTURES_USDM,
            'requires_symbols' => false,
        ],
        // Futures COIN-M
        [
            'pattern' => '/^binance\.futures_coin\.market\.trade\?symbol=(.+)$/i',
            'service_id' => 'empiriq.binance.futures_coinm.stream.trade',
            'class' => FuturesCoinMTradeStream::class,
            'tag' => self::TAG_FUTURES_COINM,
            'requires_symbols' => true,
        ],
        [
            'pattern' => '/^binance\.futures_coin\.user\.(balance_update|outbound_account_position|execution_report|external_lock_update)$/i',
            'service_id' => 'empiriq.binance.futures_coinm.stream.user_data',
            'class' => FuturesCoinMUserDataStream::class,
            'tag' => self::TAG_FUTURES_COINM,
            'requires_symbols' => false,
        ],
        // Spot
        [
            'pattern' => '/^binance\.spot\.market\.trade\?symbol=(.+)$/i',
            'service_id' => 'empiriq.binance.spot.stream.trade',
            'class' => SpotTradeStream::class,
            'tag' => self::TAG_SPOT,
            'requires_symbols' => true,
        ],
        [
            'pattern' => '/^binance\.spot\.user\.(balance_update|outbound_account_position|execution_report|external_lock_update)$/i',
            'service_id' => 'empiriq.binance.spot.stream.user_data',
            'class' => SpotUserDataStream::class,
            'tag' => self::TAG_SPOT,
            'requires_symbols' => false,
        ],
    ];

    public function __construct(
        private readonly EventDiscovery $event
    ) {
    }

    #[\Override]
    public function process(ContainerBuilder $container): void
    {
        $events = $this->event->discover($container);
        $symbolsByService = [];
        $enabledByService = [];
        foreach (self::STREAM_MAPPINGS as $mapping) {
            $serviceId = $mapping['service_id'];
            if (($mapping['requires_symbols'] ?? false) === true) {
                $symbolsByService[$serviceId] = [];
            } else {
                $enabledByService[$serviceId] = false;
            }
        }

        foreach ($events as $eventName) {
            foreach (self::STREAM_MAPPINGS as $mapping) {
                $serviceId = $mapping['service_id'];
                if (($mapping['requires_symbols'] ?? false) === true) {
                    $symbols = $this->extractSymbolsByPattern($eventName, $mapping['pattern']);
                    if ($symbols !== []) {
                        $symbolsByService[$serviceId] = array_merge(
                            $symbolsByService[$serviceId] ?? [],
                            $symbols
                        );
                    }
                    continue;
                }

                if ($this->matchesPattern($eventName, $mapping['pattern'])) {
                    $enabledByService[$serviceId] = true;
                }
            }
        }

        foreach (self::STREAM_MAPPINGS as $mapping) {
            $serviceId = $mapping['service_id'];
            if (($mapping['requires_symbols'] ?? false) === true) {
                $symbols = $this->normalizeSymbols($symbolsByService[$serviceId] ?? []);
                if ($symbols === []) {
                    continue;
                }
                $container->setDefinition(
                    $serviceId,
                    new Definition($mapping['class'], [
                        $symbols,
                    ])
                )->addTag($mapping['tag']);
                continue;
            }

            if (($enabledByService[$serviceId] ?? false) !== true) {
                continue;
            }
            $container->setDefinition(
                $serviceId,
                new Definition($mapping['class'])
            )->addTag($mapping['tag']);
        }
    }

    /**
     * @param non-empty-string $pattern
     * @return string[]
     */
    private function extractSymbolsByPattern(string $eventName, string $pattern): array
    {
        if (preg_match($pattern, $eventName, $matches) !== 1) {
            return [];
        }

        if (!isset($matches[1])) {
            return [];
        }

        return $this->splitList($matches[1]);
    }

    /**
     * @param non-empty-string $pattern
     */
    private function matchesPattern(string $eventName, string $pattern): bool
    {
        return preg_match($pattern, $eventName) === 1;
    }

    /**
     * @param string[] $symbols
     * @return string[]
     */
    private function normalizeSymbols(array $symbols): array
    {
        $normalized = [];
        foreach ($symbols as $symbol) {
            $symbol = strtoupper(trim($symbol));
            if ($symbol === '') {
                continue;
            }
            $normalized[] = $symbol;
        }

        $normalized = array_values(array_unique($normalized));
        sort($normalized);

        return $normalized;
    }

    /**
     * @return string[]
     */
    private function splitList(string $value): array
    {
        return array_map('trim', explode(',', $value));
    }
}
