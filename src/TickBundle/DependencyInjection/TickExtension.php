<?php

namespace Empiriq\TickBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;

final class TickExtension extends Extension
{
    public const PARAMETER_NAME = 'ticker.config';

    #[\Override]
    public function load(array $configs, ContainerBuilder $container): void
    {
        $container->setParameter(
            self::PARAMETER_NAME,
            $this->processConfiguration(new Configuration(), $configs)
        );
    }
}
