<?php

namespace Empiriq\BinanceManagerBundle\DependencyInjection;

use Empiriq\BinanceManagerBundle\BinanceManager;
use Empiriq\BinanceManagerBundle\Derivatives\FuturesCoinM;
use Empiriq\BinanceManagerBundle\Derivatives\FuturesUsdM;
use Empiriq\BinanceManagerBundle\Spot\Spot;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    #[\Override]
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('binance_manager');
        $rootNode = $treeBuilder->getRootNode();
        $rootNode
            ->children()
                ->scalarNode('manager_class')
                    ->defaultValue(BinanceManager::class)
                    ->cannotBeEmpty()
                ->end()
                ->append($this->addRegistryNode('futures_coin', FuturesCoinM\Registry::class, [
                    'balance' => FuturesCoinM\Repositories\BalanceRepository::class,
                    'depth' => FuturesCoinM\Repositories\DepthRepository::class,
                    'order_history' => FuturesCoinM\Repositories\OrderHistoryRepository::class,
                    'order' => FuturesCoinM\Repositories\OrderRepository::class,
                    'position_history' => FuturesCoinM\Repositories\PositionHistoryRepository::class,
                    'position' => FuturesCoinM\Repositories\PositionRepository::class,
                    'trade_history' => FuturesCoinM\Repositories\TradeHistoryRepository::class,
                    'trade' => FuturesCoinM\Repositories\TradeRepository::class,
                    'transaction_history' => FuturesCoinM\Repositories\TransactionHistoryRepository::class,
                ]))
                ->append($this->addRegistryNode('futures_usd', FuturesUsdM\Registry::class, [
                    'balance' => FuturesUsdM\Repositories\BalanceRepository::class,
                    'depth' => FuturesUsdM\Repositories\DepthRepository::class,
                    'order_history' => FuturesUsdM\Repositories\OrderHistoryRepository::class,
                    'order' => FuturesUsdM\Repositories\OrderRepository::class,
                    'position_history' => FuturesUsdM\Repositories\PositionHistoryRepository::class,
                    'position' => FuturesUsdM\Repositories\PositionRepository::class,
                    'trade_history' => FuturesUsdM\Repositories\TradeHistoryRepository::class,
                    'trade' => FuturesUsdM\Repositories\TradeRepository::class,
                    'transaction_history' => FuturesUsdM\Repositories\TransactionHistoryRepository::class,
                ]))
                ->append($this->addRegistryNode('spot', Spot\Registry::class, [
                    'balance' => Spot\Repositories\BalanceRepository::class,
                    'depth' => Spot\Repositories\DepthRepository::class,
                    'order_history' => Spot\Repositories\OrderHistoryRepository::class,
                    'order' => Spot\Repositories\OrderRepository::class,
                    'position_history' => Spot\Repositories\PositionHistoryRepository::class,
                    'position' => Spot\Repositories\PositionRepository::class,
                    'trade_history' => Spot\Repositories\TradeHistoryRepository::class,
                    'trade' => Spot\Repositories\TradeRepository::class,
                    'transaction_history' => Spot\Repositories\TransactionHistoryRepository::class,
                ]))
            ->end();

        return $treeBuilder;
    }

    private function addRegistryNode(string $name, string $registryClass, array $repositoriesMap): ArrayNodeDefinition
    {
        $treeBuilder = new TreeBuilder($name);
        /** @var ArrayNodeDefinition $node */
        $node = $treeBuilder->getRootNode();
        $children = $node->children();
        $children
            ->scalarNode('registry_class')
                ->defaultValue($registryClass)
                ->cannotBeEmpty()
            ->end();
        foreach ($repositoriesMap as $repoName => $defaultClass) {
            $children->append($this->addRepositoryNode($repoName, $defaultClass));
        }
        $children->end();

        return $node;
    }

    private function addRepositoryNode(string $name, string $defaultClass): ArrayNodeDefinition
    {
        $treeBuilder = new TreeBuilder($name);
        /** @var ArrayNodeDefinition $node */
        $node = $treeBuilder->getRootNode();
        $node
            ->beforeNormalization()
                ->always(fn ($v) => is_array($v) && array_is_list($v) ? ['arguments' => $v] : $v)
            ->end()
            ->children()
                ->scalarNode('class')
                    ->defaultValue($defaultClass)
                    ->cannotBeEmpty()
                ->end()
                ->arrayNode('arguments')
                    ->normalizeKeys(false)
                    ->variablePrototype()->end()
                    ->defaultValue([])
                ->end()
            ->end();

        return $node;
    }
}
