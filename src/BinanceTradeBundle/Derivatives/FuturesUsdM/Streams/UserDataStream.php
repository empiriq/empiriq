<?php

namespace Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\Streams;

use Empiriq\BinanceTradeBundle\Common\Interfaces\Streams\FuturesUsdMStreamInterface;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\FuturesUsdMTransport;
use Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Responses\UserData\SubscribeResponse;
use React\EventLoop\Loop;
use React\Promise\PromiseInterface;

readonly class UserDataStream implements FuturesUsdMStreamInterface
{
    public function subscribe(FuturesUsdMTransport $transport): PromiseInterface
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
