<?php

namespace Empiriq\BinanceTradeBundle;

use Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Responses\Authentication\AccountStatusResponse;
use Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Responses\General\TimeResponse;
use Empiriq\BinanceTradeBundle\Common\Exceptions\Configuration\ConfigurationException;
use Empiriq\BinanceTradeBundle\Common\Interfaces\Streams\FuturesUsdMStreamInterface;
use Empiriq\BinanceTradeBundle\Common\Interfaces\TransportInterface;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\Clients\RestApi;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\Clients\WebSocketApi;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\Clients\WebSocketStreams;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\Methods\AccountMethods;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\Methods\AuthenticationMethods;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\Methods\GeneralMethods;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\Methods\MarketDataMethods;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\Methods\MarketStreamMethods;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\Methods\TradingMethods;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\Methods\UserDataStreamMethods;
use Empiriq\Contracts\RunnableInterface;

use function React\Promise\all;

readonly class FuturesUsdMTransport implements TransportInterface, RunnableInterface
{
    use GeneralMethods;
    use MarketDataMethods;
    use AuthenticationMethods;
    use TradingMethods;
    use AccountMethods;
    use UserDataStreamMethods;
    use MarketStreamMethods;

    /**
     * @param RestApi $restApi
     * @param WebSocketApi $websocketApi
     * @param WebSocketStreams $websocketStreams
     * @param iterable<FuturesUsdMStreamInterface> $streams
     */
    public function __construct(
        private RestApi $restApi,
        private WebSocketApi $websocketApi,
        private WebSocketStreams $websocketStreams,
        private iterable $streams,
    ) {
        foreach ($this->streams as $stream) {
            if (!$stream instanceof FuturesUsdMStreamInterface) {
                throw new ConfigurationException('Invalid stream');
            }
        }
    }

    //todo share send() method for custom send

    public function run(): void
    {
        all([
            $this->websocketApi->connect()->then(function () {
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
            $this->websocketStreams->connect(),
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
            $this->websocketApi->disconnect(),
            $this->websocketStreams->disconnect(),
        ]);
    }

    public function isLoggedIn(): bool
    {
        return $this->websocketApi->isLoggedIn();
    }
}
