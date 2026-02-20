<?php

namespace Empiriq\TickerBundle\DependencyInjection\Compiler;

use Empiriq\TickerBundle\DependencyInjection\TickerExtension;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class ResolveConfigPass implements CompilerPassInterface
{
    #[\Override]
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasParameter(TickerExtension::PARAMETER_NAME)) {
            throw new \Exception('CONFIG not found');
        }
        /** @var array<string, mixed> $config */
        $config = $container->getParameter(TickerExtension::PARAMETER_NAME);
        $config = $container->getParameterBag()->resolveValue($config);
        $config = $container->resolveEnvPlaceholders($config, true);
        $container->setParameter(TickerExtension::PARAMETER_NAME, $config);
    }
}
