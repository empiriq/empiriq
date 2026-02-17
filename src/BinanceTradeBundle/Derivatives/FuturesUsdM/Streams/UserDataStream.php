<?php

namespace Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\Streams;

use Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Responses\UserData\SubscribeResponse;
use Empiriq\BinanceTradeBundle\Common\Interfaces\Streams\FuturesUsdMStreamInterface;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\FuturesUsdMMarket;
use React\EventLoop\Loop;
use React\Promise\PromiseInterface;

/**
 * User data stream for Binance USD-M Futures account events.
 *
 * This stream subscribes to Binance **user data** events related to the
 * authenticated futures account, including account updates, margin calls
 * and order/trade lifecycle events.
 *
 * This stream is enabled via the bundle configuration under the
 * `streams.user_data` section of the `futures_usd` transport:
 *
 * ```yaml
 * binance_trade:
 *   transports:
 *     futures_usd:
 *       streams:
 *         user_data: []
 * ```
 *
 * This stream emits:
 *
 * - {@see \Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Events\User\AccountUpdateEvent}
 * - {@see \Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Events\User\MarginCallEvent}
 * - {@see \Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Events\User\OrderTradeUpdateEvent}
 * - {@see \Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Events\User\TradeLiteEvent}
 */
final readonly class UserDataStream implements FuturesUsdMStreamInterface
{
    #[\Override]
    public function subscribe(FuturesUsdMMarket $market): PromiseInterface
    {
        if ($market->isLoggedIn()) {
            return $market->userDataStreamSubscribe();
        }

        return $market->createListenKey()->then(function (SubscribeResponse $response) use ($market) {
            Loop::addPeriodicTimer(30 * 60, function () use ($market, $response) {
                $market->updateListenKey($response->result->listenKey);
            });

            return $market->subscribe([$response->result->listenKey]);
        });
    }
}
