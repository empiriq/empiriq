<?php

namespace Empiriq\BinanceTradeBundle\Markets\FuturesCm\Streams;

use Empiriq\BinanceContracts\Markets\FuturesCm\Responses\UserData\SubscribeResponse;
use Empiriq\BinanceTradeBundle\Common\Interfaces\Streams\FuturesCoinMStreamInterface;
use Empiriq\BinanceTradeBundle\Markets\FuturesCm\FuturesCm;
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
    public function subscribe(FuturesCm $market): PromiseInterface
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
