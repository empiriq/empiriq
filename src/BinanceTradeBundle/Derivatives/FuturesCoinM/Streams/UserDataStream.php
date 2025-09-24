<?php

namespace Empiriq\BinanceTradeBundle\Derivatives\FuturesCoinM\Streams;

use Empiriq\BinanceTradeBundle\Common\Interfaces\Streams\FuturesCoinMStreamInterface;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesCoinM\FuturesCoinMTransport;
use Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Responses\UserData\SubscribeResponse;
use React\EventLoop\Loop;
use React\Promise\PromiseInterface;

readonly class UserDataStream implements FuturesCoinMStreamInterface
{
    public function subscribe(FuturesCoinMTransport $transport): PromiseInterface
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
