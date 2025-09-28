<?php

namespace Empiriq\TerminalBundle\DependencyInjection;

use Empiriq\TerminalBundle\RunCommand;
use Empiriq\TerminalBundle\SocketServer;
use Empiriq\TerminalBundle\TerminalApplication;
use Symfony\Component\DependencyInjection\Argument\TaggedIteratorArgument;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Reference;

final class TerminalExtension extends Extension
{
    #[\Override]
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        $serverUri = $config['serverUri'];

        $container->register(TerminalApplication::class)
            ->setAutowired(true)
            ->setAutoconfigured(true);

        $container->register(SocketServer::class)
            ->addArgument($serverUri)
            ->addArgument(new Reference(TerminalApplication::class))
            ->addTag('empiriq.runnable');

        $container->register(RunCommand::class)
            ->addArgument(new TaggedIteratorArgument('empiriq.runnable'))
            ->addArgument(new Reference('logger'))
            ->addTag('console.command');
    }
}
