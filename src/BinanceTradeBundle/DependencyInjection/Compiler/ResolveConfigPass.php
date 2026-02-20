<?php

namespace Empiriq\BinanceTradeBundle\DependencyInjection\Compiler;

use Empiriq\BinanceContracts\FuturesUmMarketInterface;
use Empiriq\BinanceTradeBundle\Common\Configs\RestConfig;
use Empiriq\BinanceTradeBundle\Common\Configs\WebSocketConfig;
use Empiriq\BinanceTradeBundle\Common\Helpers\Sanitizer;
use Empiriq\BinanceTradeBundle\Common\Helpers\Serializer;
use Empiriq\BinanceTradeBundle\Common\Signers\Ed25519Signer;
use Empiriq\BinanceTradeBundle\Common\Signers\HmacSigner;
use Empiriq\BinanceTradeBundle\Common\Signers\NullSigner;
use Empiriq\BinanceTradeBundle\Common\Signers\RsaSigner;
use Empiriq\BinanceTradeBundle\DependencyInjection\BinanceTradeExtension;
use Empiriq\BinanceTradeBundle\DependencyInjection\Configuration;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesCoinM\Clients\RestApi as FuturesCoinMRestApi;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesCoinM\Clients\WsApi as FuturesCoinMWsApi;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesCoinM\Clients\WsSubscriptions as FuturesCoinMWsSubscriptions;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesCoinM\FuturesCoinMMarket;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\Clients\RestApi as FuturesUsdMRestApi;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\Clients\WsApi as FuturesUsdMWsApi;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\Clients\WsSubscriptions as FuturesUsdMWsSubscriptions;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\FuturesUsdMMarket;
use Empiriq\BinanceTradeBundle\Spot\Spot\Clients\RestApi as SpotRestApi;
use Empiriq\BinanceTradeBundle\Spot\Spot\Clients\WsApi as SpotWsApi;
use Empiriq\BinanceTradeBundle\Spot\Spot\Clients\WsSubscriptions as SpotWsSubscriptions;
use Empiriq\BinanceTradeBundle\Spot\Spot\SpotMarket;
use Empiriq\SymfonyDependencyDiscovery\DependencyDiscovery;
use React\Http\Browser;
use Symfony\Component\DependencyInjection\Argument\TaggedIteratorArgument;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpFoundation\HeaderUtils;

final class ResolveConfigPass implements CompilerPassInterface
{
    #[\Override]
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasParameter(BinanceTradeExtension::PARAMETER_NAME)) {
            throw new \Exception('CONFIG not found');
        }
        /** @var array<string, mixed> $config */
        $config = $container->getParameter(BinanceTradeExtension::PARAMETER_NAME);
        $config = $container->getParameterBag()->resolveValue($config);
        $config = $container->resolveEnvPlaceholders($config, true);
        $container->setParameter(BinanceTradeExtension::PARAMETER_NAME, $config);
    }
}
