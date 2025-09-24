<?php

namespace Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM;

use Empiriq\BinanceTradeBundle\Common\Exceptions\Configuration\ConfigurationException;
use Empiriq\BinanceTradeBundle\Common\Interfaces\Streams\FuturesUsdMStreamInterface;
use Empiriq\BinanceTradeBundle\Common\Interfaces\TransportInterface;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\Clients\RestApi;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\Clients\WebsocketApi;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\Clients\WebsocketStreams;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\Methods\AccountMethods;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\Methods\AuthenticationMethods;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\Methods\GeneralMethods;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\Methods\MarketDataMethods;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\Methods\MarketStreamMethods;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\Methods\TradingMethods;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\Methods\UserDataStreamMethods;
use Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Responses\Authentication\AccountStatusResponse;
use Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Responses\General\TimeResponse;
use React\Promise\PromiseInterface;

use function React\Promise\all;

readonly class FuturesUsdMTransport implements TransportInterface
{
    use GeneralMethods;
    use MarketDataMethods;
    use AuthenticationMethods;
    use TradingMethods;
    use AccountMethods;
    use UserDataStreamMethods;
    use MarketStreamMethods;

    /**
     * @param FuturesUsdMStreamInterface[] $streams
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
            if (!$stream instanceof FuturesUsdMStreamInterface) {
                throw new ConfigurationException('Invalid stream');
            }
        }
    }
    //todo share send() method for custom send

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
            ->then(fn() => all(array_map(fn(FuturesUsdMStreamInterface $stream) => $stream->subscribe($this), $this->streams)))
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
