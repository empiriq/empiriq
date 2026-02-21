<?php

namespace Empiriq\BinanceRealBundle\Markets\FuturesUm;

use Empiriq\BinanceContracts\Markets\FuturesUm\FuturesUmInterface;
use Empiriq\BinanceContracts\Markets\FuturesUm\Responses\Authentication\AccountStatusResponse;
use Empiriq\BinanceContracts\Markets\FuturesUm\Responses\General\TimeResponse;
use Empiriq\BinanceRealBundle\Common\Exceptions\Configuration\ConfigurationException;
use Empiriq\BinanceRealBundle\Common\Interfaces\Streams\FuturesUsdMStreamInterface;
use Empiriq\BinanceRealBundle\Markets\FuturesUm\Clients\RestApi;
use Empiriq\BinanceRealBundle\Markets\FuturesUm\Clients\WsApi;
use Empiriq\BinanceRealBundle\Markets\FuturesUm\Clients\WsSubscriptions;
use Empiriq\BinanceRealBundle\Markets\FuturesUm\Methods\AccountMethods;
use Empiriq\BinanceRealBundle\Markets\FuturesUm\Methods\AuthenticationMethods;
use Empiriq\BinanceRealBundle\Markets\FuturesUm\Methods\GeneralMethods;
use Empiriq\BinanceRealBundle\Markets\FuturesUm\Methods\MarketDataMethods;
use Empiriq\BinanceRealBundle\Markets\FuturesUm\Methods\MarketStreamMethods;
use Empiriq\BinanceRealBundle\Markets\FuturesUm\Methods\TradingMethods;
use Empiriq\BinanceRealBundle\Markets\FuturesUm\Methods\UserDataStreamMethods;
use Empiriq\Contracts\RunnableInterface;
use React\Promise\PromiseInterface;

use function React\Promise\all;

/**
 * USD-M futures market facade combining REST/WS APIs and stream subscriptions.
 *
 * Constructed by the DI container when the market is enabled via event subscriptions.
 *
 * @api
 */
readonly class FuturesUm implements RunnableInterface, FuturesUmInterface
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
     * @param iterable<FuturesUsdMStreamInterface> $streams
     */
    public function __construct(
        public RestApi $rest,
        public WsApi $ws,
        public WsSubscriptions $subscriptions,
        private iterable $streams,
    ) {
        foreach ($this->streams as $stream) {
            if (!$stream instanceof FuturesUsdMStreamInterface) {
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
            ->then(
                fn() => all(
                    array_map(
                        fn(FuturesUsdMStreamInterface $stream) => $stream->subscribe($this),
                        is_array($this->streams) ? $this->streams : iterator_to_array($this->streams)
                    )
                )
            )
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
