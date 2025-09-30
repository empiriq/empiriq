<?php

namespace Empiriq\BinanceManagerBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Reference;

final class BinanceManagerExtension extends Extension
{
    #[\Override]
    public function load(array $configs, ContainerBuilder $container): void
    {
        $config = $this->processConfiguration(new Configuration(), $configs);
        $definition = new Definition(
            $config['manager_class'],
            [
                array_values(
                    array_map(
                        fn($item) => new Definition(
                            $item['registry_class'],
                            [
                                array_values(
                                    array_map(
                                        fn($item) => new Definition(
                                            $item['class'],
                                            array_values(
                                                array_map(
                                                    fn($arg) => is_string($arg) && str_starts_with($arg, '@')
                                                        ? new Reference(substr($arg, 1)) : $arg,
                                                    $item['arguments']
                                                )
                                            )
                                        ),
                                        array_filter(
                                            $item,
                                            fn(string $key) => $key !== 'registry_class',
                                            ARRAY_FILTER_USE_KEY
                                        )
                                    )
                                )
                            ]
                        ),
                        array_filter($config, fn(string $key) => $key !== 'manager_class', ARRAY_FILTER_USE_KEY)
                    )
                )
            ]
        );

        $container->setDefinition('empiriq.binance.manager', $definition)->addTag('empiriq.runnable');
    }
}
