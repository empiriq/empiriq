<?php

namespace Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\Streams;

use Empiriq\BinanceTradeBundle\Common\Interfaces\Streams\FuturesUsdMStreamInterface;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\FuturesUsdMTransport;
use React\Promise\PromiseInterface;

/**
 * Represents a Binance stream for Coin Margined Futures market messages.
 *
 * Constructs a stream name like "btcusdt@trade" based on the given symbol.
 */
readonly class TradeStream implements FuturesUsdMStreamInterface
{
    /**
     * @param string[] $symbols The trading pair symbol (e.g. "BTCUSDT"). Case-insensitive.
     */
    public function __construct(
        private array $symbols
    ) {
    }

    public function subscribe(FuturesUsdMTransport $transport): PromiseInterface
    {
        return $transport->subscribe(
            array_map(fn(string $symbol): string => strtolower($symbol) . '@trade', $this->symbols)
        );
    }
}
