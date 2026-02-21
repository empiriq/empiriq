<?php

namespace Empiriq\BinanceRealBundle\Markets\Spot\Streams;

use Empiriq\BinanceRealBundle\Common\Interfaces\Streams\SpotStreamInterface;
use Empiriq\BinanceRealBundle\Markets\Spot\Spot;
use React\Promise\PromiseInterface;

/**
 * Represents a binance stream for spot market messages.
 *
 * Constructs a stream name like "btcusdt@trade" based on the given symbol.
 * Constructed by the DI container when the stream is enabled via event subscriptions.
 *
 * @api
 */
final readonly class TradeStream implements SpotStreamInterface
{
    /**
     * @param string[] $symbol The trading pair symbol (e.g. "BTCUSDT"). Case-insensitive.
     */
    public function __construct(
        private array $symbol
    ) {
    }

    #[\Override]
    public function subscribe(Spot $market): PromiseInterface
    {
        return $market->subscribe(
            array_map(fn(string $symbol): string => strtolower($symbol) . '@trade', $this->symbol)
        );
    }
}
