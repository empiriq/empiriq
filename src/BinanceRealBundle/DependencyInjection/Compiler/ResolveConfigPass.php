<?php

namespace Empiriq\BinanceRealBundle\DependencyInjection\Compiler;

use Empiriq\BinanceRealBundle\DependencyInjection\BinanceRealExtension;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class ResolveConfigPass implements CompilerPassInterface
{
    #[\Override]
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasParameter(BinanceRealExtension::PARAMETER_NAME)) {
            throw new \Exception('CONFIG not found');
        }
        /** @var array<string, mixed> $config */
        $config = $container->getParameter(BinanceRealExtension::PARAMETER_NAME);
        $config = $container->getParameterBag()->resolveValue($config);
        $config = $container->resolveEnvPlaceholders($config, true);
        $container->setParameter(BinanceRealExtension::PARAMETER_NAME, $config);
    }
}
