<?php

namespace Empiriq\Server\DependencyInjection;

use Empiriq\Contracts\EnvironmentInterface;
use Empiriq\Server\ServerCommand;
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

        $container->autowire('Empiriq\Server')
            ->setAutowired(true)
            ->setAutoconfigured(true);

        // Автоконфигурация: все EnvironmentInterface получат тег автоматически
        $container->registerForAutoconfiguration(EnvironmentInterface::class)
            ->addTag('app.environment');

        // Регистрируем команду, в конструктор попадёт ленивый итератор
        $container->register(ServerCommand::class, ServerCommand::class)
            ->addArgument(new Reference('logger'))
            ->addArgument(new TaggedIteratorArgument('app.environment'))
            ->addTag('console.command');
    }
}
