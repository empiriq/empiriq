<?php

namespace Empiriq\TerminalBundle\DependencyInjection;

use Empiriq\Contracts\RunnableInterface;
use Empiriq\TerminalBundle\RunCommand;
use Symfony\Component\DependencyInjection\Argument\TaggedIteratorArgument;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Reference;

class ServerExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        $port = $config['port'];
        $password = $config['password'];

        $container->autowire('Empiriq\Server')
            ->setAutowired(true)
            ->setAutoconfigured(true);

        $container->registerForAutoconfiguration(RunnableInterface::class)
            ->addTag('runnable');

        $container->register(RunCommand::class, RunCommand::class)
            ->addArgument(new Reference('logger'))
            ->addArgument(new TaggedIteratorArgument('runnable'))
            ->addTag('console.command');
    }
}
