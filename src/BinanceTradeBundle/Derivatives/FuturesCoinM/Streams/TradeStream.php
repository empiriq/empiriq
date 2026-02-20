<?php

namespace Empiriq\BinanceTradeBundle\Derivatives\FuturesCoinM\Streams;

use Empiriq\BinanceTradeBundle\Common\Interfaces\Streams\FuturesCoinMStreamInterface;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesCoinM\FuturesCoinMMarket;
use React\Promise\PromiseInterface;

/**
 * Represents a Binance stream for USD Margined Futures market messages.
 *
 * Constructs a stream name like "btcusdt@trade" based on the given symbol.
 * Constructed by the DI container when the stream is enabled via event subscriptions.
 *
 * @api
 */
final readonly class TradeStream implements FuturesCoinMStreamInterface
{
    /**
     * @param string[] $symbol The trading pair symbol (e.g. "BTCUSDT"). Case-insensitive.
     */
    public function __construct(
        private array $symbol
    ) {
    }

    #[\Override]
    public function subscribe(FuturesCoinMMarket $market): PromiseInterface
    {
        return $market->subscribe(
            array_map(fn(string $symbol): string => strtolower($symbol) . '@trade', $this->symbol)
        );
    }
}
