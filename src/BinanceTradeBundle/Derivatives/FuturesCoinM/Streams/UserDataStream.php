<?php

namespace Empiriq\BinanceTradeBundle\Derivatives\FuturesCoinM\Streams;

use Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Responses\UserData\SubscribeResponse;
use Empiriq\BinanceTradeBundle\Common\Interfaces\Streams\FuturesCoinMStreamInterface;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesCoinM\FuturesCoinMMarket;
use React\EventLoop\Loop;
use React\Promise\PromiseInterface;

/**
 * User data stream for Binance COIN-M Futures account events.
 *
 * Constructed by the DI container when the stream is enabled via event subscriptions.
 *
 * @api
 */
final readonly class UserDataStream implements FuturesCoinMStreamInterface
{
    #[\Override]
    public function subscribe(FuturesCoinMMarket $market): PromiseInterface
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
