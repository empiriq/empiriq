<?php

namespace Empiriq\BinanceTradeBundle\Spot\Spot;

use Empiriq\BinanceTradeBundle\Common\Exceptions\Configuration\ConfigurationException;
use Empiriq\BinanceTradeBundle\Common\Interfaces\Streams\SpotStreamInterface;
use Empiriq\BinanceTradeBundle\Common\Interfaces\TransportInterface;
use Empiriq\BinanceTradeBundle\Spot\Spot\Clients\RestApi;
use Empiriq\BinanceTradeBundle\Spot\Spot\Clients\WebsocketApi;
use Empiriq\BinanceTradeBundle\Spot\Spot\Clients\WebsocketStreams;
use Empiriq\BinanceTradeBundle\Spot\Spot\Methods\AccountMethods;
use Empiriq\BinanceTradeBundle\Spot\Spot\Methods\AuthenticationMethods;
use Empiriq\BinanceTradeBundle\Spot\Spot\Methods\GeneralMethods;
use Empiriq\BinanceTradeBundle\Spot\Spot\Methods\MarketDataMethods;
use Empiriq\BinanceTradeBundle\Spot\Spot\Methods\MarketStreamMethods;
use Empiriq\BinanceTradeBundle\Spot\Spot\Methods\TradingMethods;
use Empiriq\BinanceTradeBundle\Spot\Spot\Methods\UserDataStreamMethods;
use Empiriq\BinanceContracts\Spot\Spot\Responses\Account\AccountStatusResponse;
use Empiriq\BinanceContracts\Spot\Spot\Responses\General\TimeResponse;
use React\Promise\PromiseInterface;

use function React\Promise\all;

readonly class SpotTransport implements TransportInterface
{
    use GeneralMethods;
    use MarketDataMethods;
    use AuthenticationMethods;
    use TradingMethods;
    use AccountMethods;
    use UserDataStreamMethods;
    use MarketStreamMethods;

    /**
     * @param SpotStreamInterface[] $streams
     * @param RestApi $restApi
     * @param WebsocketApi $websocketApi
     * @param WebsocketStreams $websocketStreams
     */
    public function __construct(
        private array $streams,
        private RestApi $restApi,
        private WebsocketApi $websocketApi,
        private WebsocketStreams $websocketStreams,
    ) {
        foreach ($this->streams as $stream) {
            if (!$stream instanceof SpotStreamInterface) {
                throw new ConfigurationException('Invalid stream');
            }
        }
    }

    public function run(): PromiseInterface
    {
        return all([
            $this->websocketApi->run()->then(function () {
                return $this->time();
            })->then(function (TimeResponse $response) {
                $this->restApi->calculateTimeOffset($response->result->serverTime);
                $this->websocketApi->calculateTimeOffset($response->result->serverTime);
                return $this;
            })->then(function () {
                return $this->websocketApi->canLogIn() ? $this->sessionLogon() : null;
            })->then(function (?AccountStatusResponse $response) {
                $this->websocketApi->setLoggedIn((bool)$response);
                return $this;
            }),
            $this->websocketStreams->run(),
        ])
            ->then(fn() => all(array_map(fn(SpotStreamInterface $stream) => $stream->subscribe($this), $this->streams)))
            ->then(fn() => $this);
    }

    public function shutdown(): void
    {
        $this->websocketApi->shutdown();
        $this->websocketStreams->shutdown();
    }

    public function isLoggedIn(): bool
    {
        return $this->websocketApi->isLoggedIn();
    }
}
