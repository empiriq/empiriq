<?php

namespace Empiriq\BinanceTradeBundle\Derivatives\FuturesCoinM;

use Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Responses\Account\AccountStatusResponse;
use Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Responses\General\TimeResponse;
use Empiriq\BinanceTradeBundle\Common\Exceptions\Configuration\ConfigurationException;
use Empiriq\BinanceTradeBundle\Common\Interfaces\Streams\FuturesCoinMStreamInterface;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesCoinM\Clients\RestApi;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesCoinM\Clients\WsApi;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesCoinM\Clients\WsSubscriptions;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesCoinM\Methods\AccountMethods;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesCoinM\Methods\AuthenticationMethods;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesCoinM\Methods\GeneralMethods;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesCoinM\Methods\MarketDataMethods;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesCoinM\Methods\MarketStreamMethods;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesCoinM\Methods\TradingMethods;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesCoinM\Methods\UserDataStreamMethods;
use Empiriq\Contracts\RunnableInterface;

use function React\Promise\all;

/**
 * COIN-M futures market facade combining REST/WS APIs and stream subscriptions.
 *
 * Constructed by the DI container when the market is enabled via event subscriptions.
 *
 * @api
 */
readonly class FuturesCoinMMarket implements RunnableInterface
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
     * @param iterable<FuturesCoinMStreamInterface> $streams
     */
    public function __construct(
        public RestApi $rest,
        public WsApi $ws,
        public WsSubscriptions $subscriptions,
        private iterable $streams,
    ) {
        foreach ($this->streams as $stream) {
            if (!$stream instanceof FuturesCoinMStreamInterface) {
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
            ->then(fn() => all(
                array_map(
                    fn(FuturesCoinMStreamInterface $stream) => $stream->subscribe($this),
                    $this->streams
                )
            ));
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
