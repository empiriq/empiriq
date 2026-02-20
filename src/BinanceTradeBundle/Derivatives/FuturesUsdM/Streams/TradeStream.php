<?php

namespace Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\Streams;

use Empiriq\BinanceTradeBundle\Common\Interfaces\Streams\FuturesUsdMStreamInterface;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\FuturesUsdMMarket;
use React\Promise\PromiseInterface;

/**
 * Trade stream for Binance USD-M Futures market.
 *
 * This stream subscribes to Binance **trade** events for the specified
 * USD-M Futures symbols.
 *
 * This stream is enabled via the bundle configuration under the
 * `streams.trade` section of the `futures_usd` transport:
 *
 * ```yaml
 * binance_trade:
 *   transports:
 *     futures_usd:
 *       streams:
 *         trade: [['BTCUSDT']]
 * ```
 *
 * This stream emits:
 *
 * - {@see \Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Events\Market\TradeEvent}
 *
 * Constructed by the DI container when the stream is enabled via event subscriptions.
 *
 * @api
 */
final readonly class TradeStream implements FuturesUsdMStreamInterface
{
    /**
     * @param string[] $symbol The trading pair symbol (e.g. "BTCUSDT"). Case-insensitive.
     */
    public function __construct(
        private array $symbol
    ) {
    }

    #[\Override]
    public function subscribe(FuturesUsdMMarket $market): PromiseInterface
    {
        return $market->subscribe(
            array_map(fn(string $symbol): string => strtolower($symbol) . '@trade', $this->symbol)
        );
    }
}
