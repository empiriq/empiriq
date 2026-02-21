<?php

namespace Empiriq\BinanceRealBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;

final class BinanceTradeExtension extends Extension
{
    public const PARAMETER_NAME = 'binance_trade.config';
    public const DEFAULTS = 'binance_trade.defaults';

    #[\Override]
    public function load(array $configs, ContainerBuilder $container): void
    {
        $config = $this->processConfiguration(new Configuration(), $configs);
        $container->setParameter(self::PARAMETER_NAME, $config);
        $container->setParameter(self::DEFAULTS, [
            'mainnet' => [
                'spot' => [
                    'rest_api' => 'https://api.binance.com',
                    'websocket_api' => 'wss://ws-api.binance.com/ws-api/v3',
                    'websocket_market_streams' => 'wss://stream.binance.com:9443/ws',
                ],
                'futures_usdm' => [
                    'rest_api' => 'https://fapi.binance.com',
                    'websocket_api' => 'wss://ws-fapi.binance.com/ws-fapi/v1',
                    'websocket_market_streams' => 'wss://fstream.binance.com/ws',
                ],
                'futures_coinm' => [
                    'rest_api' => 'https://dapi.binance.com',
                    'websocket_api' => 'wss://ws-dapi.binance.com/ws-dapi/v1',
                    'websocket_market_streams' => 'wss://dstream.binance.com/ws',
                ],
                'options' => [
                    'rest_api' => 'https://eapi.binance.com',
                    'websocket_api' => 'wss://ws-eapi.binance.com/ws-eapi/v1',
                    'websocket_market_streams' => 'wss://estream.binance.com/ws',
                ],
            ],
            'testnet' => [
                'spot' => [
                    'rest_api' => 'https://testnet.binance.vision',
                    'websocket_api' => 'wss://testnet.binance.vision/ws-api/v3',
                    'websocket_market_streams' => 'wss://testnet.binance.vision/ws',
                ],
                'futures_usdm' => [
                    'rest_api' => 'https://testnet.binancefuture.com',
                    'websocket_api' => 'wss://testnet.binancefuture.com/ws-fapi/v1',
                    'websocket_market_streams' => 'wss://fstream.binancefuture.com/ws',
                ],
                'futures_coinm' => [
                    'rest_api' => 'https://testnet.binancefuture.com/dapi',
                    'websocket_api' => 'wss://testnet.binancefuture.com/ws-dapi/v1',
                    'websocket_market_streams' => 'wss://dstream.binancefuture.com/ws',
                ],
                'options' => [
                    'rest_api' => 'https://testnet.binancefuture.com/eapi',
                    'websocket_api' => 'wss://testnet.binancefuture.com/ws-eapi/v1',
                    'websocket_market_streams' => 'wss://testnet.binancefuture.com/ws',
                ],
            ],
        ]);
    }
}
