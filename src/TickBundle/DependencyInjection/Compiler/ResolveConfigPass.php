<?php

namespace Empiriq\TickBundle\DependencyInjection\Compiler;

use Empiriq\TickBundle\DependencyInjection\TickExtension;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class ResolveConfigPass implements CompilerPassInterface
{
    #[\Override]
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasParameter(TickExtension::PARAMETER_NAME)) {
            throw new \Exception('CONFIG not found');
        }
        /** @var array<string, mixed> $config */
        $config = $container->getParameter(TickExtension::PARAMETER_NAME);
        $config = $container->getParameterBag()->resolveValue($config);
        $config = $container->resolveEnvPlaceholders($config, true);
        $container->setParameter(TickExtension::PARAMETER_NAME, $config);
    }
}
