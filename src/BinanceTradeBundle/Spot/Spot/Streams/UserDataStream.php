<?php

namespace Empiriq\BinanceTradeBundle\Spot\Spot\Streams;

use Empiriq\BinanceContracts\Spot\Spot\Responses\UserData\SubscribeResponse;
use Empiriq\BinanceTradeBundle\Common\Interfaces\Streams\SpotStreamInterface;
use Empiriq\BinanceTradeBundle\SpotTransport;
use React\EventLoop\Loop;
use React\Promise\PromiseInterface;

final readonly class UserDataStream implements SpotStreamInterface
{
    #[\Override]
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
