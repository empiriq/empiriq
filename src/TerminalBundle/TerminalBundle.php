<?php

namespace Empiriq\TerminalBundle;

use Empiriq\TerminalBundle\DependencyInjection\TerminalExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @api TerminalBundle integrates runnable terminal services into a Symfony application.
 */
final class TerminalBundle extends Bundle
{
    #[\Override]
    protected function getContainerExtensionClass(): string
    {
        return TerminalExtension::class;
    }

    #[\Override]
    public function getContainerExtension(): ?ExtensionInterface
    {
        return new TerminalExtension();
    }

    #[\Override]
    public function build(ContainerBuilder $container): void
    {
    }
}
