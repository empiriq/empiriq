<?php

namespace Empiriq\BinanceTradeBundle\Derivatives\FuturesCoinM\Streams;

use Empiriq\BinanceTradeBundle\Common\Interfaces\Streams\FuturesCoinMStreamInterface;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesCoinM\FuturesCoinMTransport;
use React\Promise\PromiseInterface;

/**
 * Represents a Binance stream for USD Margined Futures market messages.
 *
 * Constructs a stream name like "btcusdt@trade" based on the given symbol.
 */
readonly class TradeStream implements FuturesCoinMStreamInterface
{
    /**
     * @param string[] $symbols The trading pair symbol (e.g. "BTCUSDT"). Case-insensitive.
     */
    public function __construct(
        private array $symbols
    ) {
    }

    public function subscribe(FuturesCoinMTransport $transport): PromiseInterface
    {
        return $transport->subscribe(
            array_map(fn(string $symbol): string => strtolower($symbol) . '@trade', $this->symbols)
        );
    }
}
