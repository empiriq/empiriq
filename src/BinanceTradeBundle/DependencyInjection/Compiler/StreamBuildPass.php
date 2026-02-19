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
     *     patterns: array<int, non-empty-string>,
     *     service_id: string,
     *     class: class-string,
     *     tag: string,
     *     requires_symbols: bool
     * }>
     */
    private const STREAM_MAPPINGS = [
        // Futures USD-M
        [
            'patterns' => [
                '/^binance\.futures_usd\.market\.trade\?symbol=(.+)$/i',
            ],
            'service_id' => 'empiriq.binance.futures_usdm.stream.trade',
            'class' => FuturesUsdMTradeStream::class,
            'tag' => self::TAG_FUTURES_USDM,
            'requires_symbols' => true,
        ],
        [
            'patterns' => [
                '/^binance\.futures_usd\.user\.account_update$/i',
                '/^binance\.futures_usd\.user\.order_trade_update$/i',
                '/^binance\.futures_usd\.user\.margin_call$/i',
                '/^binance\.futures_usd\.user\.trade_lite$/i',
            ],
            'service_id' => 'empiriq.binance.futures_usdm.stream.user_data',
            'class' => FuturesUsdMUserDataStream::class,
            'tag' => self::TAG_FUTURES_USDM,
            'requires_symbols' => false,
        ],
        // Futures COIN-M
        [
            'patterns' => [
                '/^binance\.futures_coin\.market\.trade\?symbol=(.+)$/i',
            ],
            'service_id' => 'empiriq.binance.futures_coinm.stream.trade',
            'class' => FuturesCoinMTradeStream::class,
            'tag' => self::TAG_FUTURES_COINM,
            'requires_symbols' => true,
        ],
        [
            'patterns' => [
                '/^binance\.futures_coin\.user\.balance_update$/i',
                '/^binance\.futures_coin\.user\.outbound_account_position$/i',
                '/^binance\.futures_coin\.user\.execution_report$/i',
                '/^binance\.futures_coin\.user\.external_lock_update$/i',
            ],
            'service_id' => 'empiriq.binance.futures_coinm.stream.user_data',
            'class' => FuturesCoinMUserDataStream::class,
            'tag' => self::TAG_FUTURES_COINM,
            'requires_symbols' => false,
        ],
        // Spot
        [
            'patterns' => [
                '/^binance\.spot\.market\.trade\?symbol=(.+)$/i',
            ],
            'service_id' => 'empiriq.binance.spot.stream.trade',
            'class' => SpotTradeStream::class,
            'tag' => self::TAG_SPOT,
            'requires_symbols' => true,
        ],
        [
            'patterns' => [
                '/^binance\.spot\.user\.balance_update$/i',
                '/^binance\.spot\.user\.outbound_account_position$/i',
                '/^binance\.spot\.user\.execution_report$/i',
                '/^binance\.spot\.user\.external_lock_update$/i',
            ],
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
                    $symbols = $this->extractSymbolsByPatterns($eventName, $mapping['patterns']);
                    if ($symbols !== []) {
                        $symbolsByService[$serviceId] = array_merge(
                            $symbolsByService[$serviceId] ?? [],
                            $symbols
                        );
                    }
                    continue;
                }

                if ($this->matchesAnyPattern($eventName, $mapping['patterns'])) {
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
     * @param array<int, non-empty-string> $patterns
     * @return string[]
     */
    private function extractSymbolsByPatterns(string $eventName, array $patterns): array
    {
        $symbols = [];
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $eventName, $matches) !== 1) {
                continue;
            }
            if (!isset($matches[1])) {
                continue;
            }
            $symbols = array_merge($symbols, $this->splitList($matches[1]));
        }

        return $symbols;
    }

    /**
     * @param array<int, non-empty-string> $patterns
     */
    private function matchesAnyPattern(string $eventName, array $patterns): bool
    {
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $eventName) === 1) {
                return true;
            }
        }

        return false;
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
