<?php

namespace Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM;

use Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Responses\Authentication\AccountStatusResponse;
use Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Responses\General\TimeResponse;
use Empiriq\BinanceTradeBundle\Common\Exceptions\Configuration\ConfigurationException;
use Empiriq\BinanceTradeBundle\Common\Interfaces\Streams\FuturesUsdMStreamInterface;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\Clients\RestApi;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\Clients\WsApi;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\Clients\WsSubscriptions;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\Methods\AccountMethods;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\Methods\AuthenticationMethods;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\Methods\GeneralMethods;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\Methods\MarketDataMethods;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\Methods\MarketStreamMethods;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\Methods\TradingMethods;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\Methods\UserDataStreamMethods;
use Empiriq\Contracts\RunnableInterface;

use function React\Promise\all;

/**
 * USD-M futures market facade combining REST/WS APIs and stream subscriptions.
 *
 * Constructed by the DI container when the market is enabled via event subscriptions.
 *
 * @api
 */
readonly class FuturesUsdMMarket implements RunnableInterface
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
            ->then(
                fn() => all(
                    array_map(
                        fn(FuturesUsdMStreamInterface $stream) => $stream->subscribe($this),
                        is_array($this->streams) ? $this->streams : iterator_to_array($this->streams)
                    )
                )
            );
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
