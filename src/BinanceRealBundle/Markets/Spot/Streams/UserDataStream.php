<?php

namespace Empiriq\BinanceRealBundle\Markets\Spot\Streams;

use Empiriq\BinanceContracts\Markets\Spot\Responses\UserData\SubscribeResponse;
use Empiriq\BinanceRealBundle\Common\Interfaces\Streams\SpotStreamInterface;
use Empiriq\BinanceRealBundle\Markets\Spot\Spot;
use React\EventLoop\Loop;
use React\Promise\PromiseInterface;

/**
 * User data stream for Binance Spot account events.
 *
 * Constructed by the DI container when the stream is enabled via event subscriptions.
 *
 * @api
 */
final readonly class UserDataStream implements SpotStreamInterface
{
    #[\Override]
    public function subscribe(Spot $market): PromiseInterface
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
