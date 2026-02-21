<?php

namespace Empiriq\BinanceRealBundle\Markets\FuturesCm;

use Empiriq\BinanceContracts\Markets\FuturesCm\Responses\Account\AccountStatusResponse;
use Empiriq\BinanceContracts\Markets\FuturesCm\Responses\General\TimeResponse;
use Empiriq\BinanceRealBundle\Common\Exceptions\Configuration\ConfigurationException;
use Empiriq\BinanceRealBundle\Common\Interfaces\Streams\FuturesCoinMStreamInterface;
use Empiriq\BinanceRealBundle\Markets\FuturesCm\Clients\RestApi;
use Empiriq\BinanceRealBundle\Markets\FuturesCm\Clients\WsApi;
use Empiriq\BinanceRealBundle\Markets\FuturesCm\Clients\WsSubscriptions;
use Empiriq\BinanceRealBundle\Markets\FuturesCm\Methods\AccountMethods;
use Empiriq\BinanceRealBundle\Markets\FuturesCm\Methods\AuthenticationMethods;
use Empiriq\BinanceRealBundle\Markets\FuturesCm\Methods\GeneralMethods;
use Empiriq\BinanceRealBundle\Markets\FuturesCm\Methods\MarketDataMethods;
use Empiriq\BinanceRealBundle\Markets\FuturesCm\Methods\MarketStreamMethods;
use Empiriq\BinanceRealBundle\Markets\FuturesCm\Methods\TradingMethods;
use Empiriq\BinanceRealBundle\Markets\FuturesCm\Methods\UserDataStreamMethods;
use Empiriq\Contracts\RunnableInterface;
use React\Promise\PromiseInterface;

use function React\Promise\all;

/**
 * COIN-M futures market facade combining REST/WS APIs and stream subscriptions.
 *
 * Constructed by the DI container when the market is enabled via event subscriptions.
 *
 * @api
 */
readonly class FuturesCm implements RunnableInterface
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

    #[\Override]
    public function run(): PromiseInterface
    {
        return all([
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
            ))
            ->then(static fn() => null);
    }

    #[\Override]
    public function shutdown(): PromiseInterface
    {
        return all([
            $this->ws->disconnect(),
            $this->subscriptions->disconnect(),
        ])->then(static fn() => null);
    }

    public function isLoggedIn(): bool
    {
        return $this->ws->isLoggedIn();
    }
}
