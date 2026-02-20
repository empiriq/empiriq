<?php

namespace Empiriq\BinanceContracts\Markets\FuturesUm;

use Empiriq\BinanceContracts\Markets\FuturesUm\Requests\MarketData\Depth;
use Empiriq\BinanceContracts\Markets\FuturesUm\Responses\General\ExchangeInfoResponse;
use Empiriq\BinanceContracts\Markets\FuturesUm\Responses\General\PingResponse;
use Empiriq\BinanceContracts\Markets\FuturesUm\Responses\General\TimeResponse;
use Empiriq\BinanceContracts\Markets\FuturesUm\Responses\MarketData\DepthResponse;
use Empiriq\BinanceContracts\Markets\FuturesUm\Responses\UserData\SubscribeResponse;
use React\Promise\PromiseInterface;

/**
 * USD-M futures market facade combining REST/WS APIs and stream subscriptions.
 *
 * Constructed by the DI container when the market is enabled via event subscriptions.
 *
 * @api
 */
interface FuturesUmInterface
{
    /**
     * Account balance info
     */
    public function accountBalanceV2(): PromiseInterface;

    /**
     * Log in with API key
     * @return PromiseInterface
     */
    public function sessionLogon(): PromiseInterface;

    /**
     * Log out of the session
     * @return PromiseInterface
     */
    public function sessionLogout(): PromiseInterface;

    /**
     * Query session status
     * @return PromiseInterface
     */
    public function sessionStatus(): PromiseInterface;

    /**
     * Ping the REST API to test connectivity.
     * @return PromiseInterface<PingResponse>
     */
    public function ping(): PromiseInterface;

    /**
     * Get Binance server time.
     * @return PromiseInterface<TimeResponse>
     */
    public function time(): PromiseInterface;

    /**
     * Fetch exchange information.
     * @return PromiseInterface<ExchangeInfoResponse>
     */
    public function exchangeInfo(): PromiseInterface;

    /**
     * @param Depth $payload
     * @return PromiseInterface<DepthResponse>
     * @link
     */
    public function depth(Depth $payload): PromiseInterface;

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
