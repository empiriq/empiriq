<?php

namespace Empiriq\BinanceContracts;

use Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Requests\MarketData\Depth;
use Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Responses\General\PingResponse;
use Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Responses\General\TimeResponse;
use Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Responses\MarketData\DepthResponse;
use Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Responses\UserData\SubscribeResponse;
use React\Promise\PromiseInterface;

/**
 * USD-M futures market facade combining REST/WS APIs and stream subscriptions.
 *
 * Constructed by the DI container when the market is enabled via event subscriptions.
 *
 * @api
 */
interface FuturesUmMarketInterface
{
    /**
     * Account balance info
     *
     * @see https://developers.binance.com/docs/derivatives/usds-margined-futures/account/websocket-api
     */
    public function accountBalanceV2(): PromiseInterface;

    /**
     * Log in with API key
     *
     * @link https://developers.binance.com/docs/derivatives/usds-margined-futures/websocket-api-general-info#log-in-with-api-key-signed
     * @return PromiseInterface
     */
    public function sessionLogon(): PromiseInterface;

    /**
     * Log out of the session
     *
     * @link  https://developers.binance.com/docs/derivatives/usds-margined-futures/websocket-api-general-info#log-out-of-the-session
     * @return PromiseInterface
     */
    public function sessionLogout(): PromiseInterface;

    /**
     * Query session status
     *
     * @link https://developers.binance.com/docs/derivatives/usds-margined-futures/websocket-api-general-info#query-session-status
     * @return PromiseInterface
     */
    public function sessionStatus(): PromiseInterface;

    /**
     * @link https://developers.binance.com/docs/derivatives/usds-margined-futures/market-data/rest-api
     * @return PromiseInterface<PingResponse>
     */
    public function ping(): PromiseInterface;

    /**
     * @link https://developers.binance.com/docs/derivatives/usds-margined-futures/market-data/rest-api/Check-Server-Time
     * @return PromiseInterface<TimeResponse>
     */
    public function time(): PromiseInterface;

    /**
     * @link https://developers.binance.com/docs/derivatives/usds-margined-futures/market-data/rest-api/Exchange-Information
     * @return PromiseInterface<TimeResponse>
     */
    public function exchangeInfo(): PromiseInterface;

    /**
     * @param Depth $payload
     * @return PromiseInterface<DepthResponse>
     * @link
     */
    public function depth(Depth $payload): PromiseInterface;

    /**
     * @see https://developers.binance.com/docs/derivatives/usds-margined-futures/websocket-market-streams/Live-Subscribing-Unsubscribing-to-streams#subscribe-to-a-stream
     * @param array $streams like ["btcusdt@aggTrade", "btcusdt@depth"]
     * @return PromiseInterface
     */
    public function subscribe(array $streams): PromiseInterface;

    /**
     * @param array $streams
     * @return PromiseInterface
     */
    public function unsubscribe(array $streams): PromiseInterface;

    /**
     * @see https://developers.binance.com/docs/derivatives/usds-margined-futures/websocket-market-streams/Live-Subscribing-Unsubscribing-to-streams#listing-subscriptions
     * @return PromiseInterface
     */
    public function listSubscriptions(): PromiseInterface;

    /**
     * @param array $property
     * @return PromiseInterface
     */
    public function setProperty(array $property): PromiseInterface;

    /**
     * @param array $property
     * @return PromiseInterface
     */
    public function getProperty(array $property): PromiseInterface;

    /**
     * Subscribe to User Data Stream
     * @see https://developers.binance.com/docs/derivatives/usds-margined-futures/user-data-streams/Start-User-Data-Stream-Wsp
     * @return PromiseInterface<SubscribeResponse>
     */
    public function userDataStreamSubscribe(): PromiseInterface;

    /**
     * Unsubscribe from User Data Stream
     * @see https://developers.binance.com/docs/derivatives/usds-margined-futures/user-data-streams/Close-User-Data-Stream-Wsp
     * @return PromiseInterface<SubscribeResponse>
     */
    public function userDataStreamUnsubscribe(): PromiseInterface;

    public function deleteListenKey(string $listenKey): PromiseInterface;
}
