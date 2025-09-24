<?php

namespace Empiriq\BinanceTradeBundle\Spot\Spot\Streams;

use Empiriq\BinanceTradeBundle\Common\Interfaces\Streams\SpotStreamInterface;
use Empiriq\BinanceTradeBundle\Spot\Spot\SpotTransport;
use Empiriq\BinanceContracts\Spot\Spot\Responses\UserData\SubscribeResponse;
use React\EventLoop\Loop;
use React\Promise\PromiseInterface;

readonly class UserDataStream implements SpotStreamInterface
{
    public function subscribe(SpotTransport $transport): PromiseInterface
    {
        if ($transport->isLoggedIn()) {
            return $transport->userDataStreamSubscribe();
        }

        return $transport->createListenKey()->then(function (SubscribeResponse $response) use ($transport) {
            Loop::addPeriodicTimer(30 * 60, function () use ($transport, $response) {
                $transport->updateListenKey($response->result->listenKey);
            });

            return $transport->subscribe([$response->result->listenKey]);
        });
    }
}
