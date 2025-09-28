<?php

namespace Empiriq\TerminalBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    #[\Override]
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('terminal');
        /** @psalm-suppress UndefinedMethod */
        $treeBuilder
            ->getRootNode()
            ->children()
            ->scalarNode('serverUri')->isRequired()->cannotBeEmpty()->end()
            ->end();

        return $treeBuilder;
    }
}
