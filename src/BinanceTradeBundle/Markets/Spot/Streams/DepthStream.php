<?php

namespace Empiriq\BinanceTradeBundle\Markets\Spot\Streams;

use Empiriq\BinanceTradeBundle\Common\Interfaces\Streams\SpotStreamInterface;
use Empiriq\BinanceTradeBundle\Markets\Spot\SpotMarket;
use React\Promise\PromiseInterface;

/**
 * Represents a binance stream for spot market messages.
 *
 * Constructs a stream name like "btcusdt@depth" based on the given symbol.
 * Constructed by the DI container when the stream is enabled via event subscriptions.
 *
 * @api
 */
final readonly class DepthStream implements SpotStreamInterface
{
    /**
     * @param string[] $symbols The trading pair symbol (e.g. "BTCUSDT"). Case-insensitive.
     */
    public function __construct(
        private array $symbols
    ) {
    }

    #[\Override]
    public function subscribe(SpotMarket $market): PromiseInterface
    {
        return $market->subscribe(
            array_map(fn(string $symbol): string => strtolower($symbol) . '@depth', $this->symbols)
        );
    }
}
