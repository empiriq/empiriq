<?php

namespace Empiriq\BinanceTradeBundle\Spot\Spot;

use Empiriq\BinanceContracts\Spot\Spot\Responses\Account\AccountStatusResponse;
use Empiriq\BinanceContracts\Spot\Spot\Responses\General\TimeResponse;
use Empiriq\BinanceTradeBundle\Common\Exceptions\Configuration\ConfigurationException;
use Empiriq\BinanceTradeBundle\Common\Interfaces\Streams\SpotStreamInterface;
use Empiriq\BinanceTradeBundle\Spot\Spot\Clients\RestApi;
use Empiriq\BinanceTradeBundle\Spot\Spot\Clients\WsApi;
use Empiriq\BinanceTradeBundle\Spot\Spot\Clients\WsSubscriptions;
use Empiriq\BinanceTradeBundle\Spot\Spot\Methods\AccountMethods;
use Empiriq\BinanceTradeBundle\Spot\Spot\Methods\AuthenticationMethods;
use Empiriq\BinanceTradeBundle\Spot\Spot\Methods\GeneralMethods;
use Empiriq\BinanceTradeBundle\Spot\Spot\Methods\MarketDataMethods;
use Empiriq\BinanceTradeBundle\Spot\Spot\Methods\MarketStreamMethods;
use Empiriq\BinanceTradeBundle\Spot\Spot\Methods\TradingMethods;
use Empiriq\BinanceTradeBundle\Spot\Spot\Methods\UserDataStreamMethods;
use Empiriq\Contracts\RunnableInterface;

use function React\Promise\all;

readonly class SpotMarket implements RunnableInterface
{
    use GeneralMethods;
    use MarketDataMethods;
    use AuthenticationMethods;
    use TradingMethods;
    use AccountMethods;
    use UserDataStreamMethods;
    use MarketStreamMethods;

    /**
     * @param RestApi $rest
     * @param WsApi $ws
     * @param WsSubscriptions $subscriptions
     * @param iterable<SpotStreamInterface> $streams
     */
    public function __construct(
        public RestApi $rest,
        public WsApi $ws,
        public WsSubscriptions $subscriptions,
        private iterable $streams,
    ) {
        foreach ($this->streams as $stream) {
            if (!$stream instanceof SpotStreamInterface) {
                throw new ConfigurationException('Invalid stream');
            }
        }
    }

    public function run(): void
    {
        all([
            $this->ws->connect()->then(function () {
                return $this->time();
            })->then(function (TimeResponse $response) {
                $this->rest->calculateTimeOffset($response->result->serverTime);
                $this->ws->calculateTimeOffset($response->result->serverTime);
                return $this;
            })->then(function () {
                return $this->ws->canLogIn() ? $this->sessionLogon() : null;
            })->then(function (?AccountStatusResponse $response) {
                $this->ws->setLoggedIn((bool)$response);
                return $this;
            }),
            $this->subscriptions->connect(),
        ])
        ->then(fn() => all(array_map(fn(SpotStreamInterface $stream) => $stream->subscribe($this), $this->streams)));
    }

    public function shutdown(): void
    {
        all([
            $this->ws->disconnect(),
            $this->subscriptions->disconnect(),
        ]);
    }

    public function isLoggedIn(): bool
    {
        return $this->ws->isLoggedIn();
    }
}
