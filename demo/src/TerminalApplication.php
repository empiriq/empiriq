<?php

namespace Empiriq\Demo;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\HttpKernel\KernelInterface;

final class TerminalApplication extends Application
{
    public function __construct(
        KernelInterface $kernel,
    ) {
        parent::__construct($kernel);
        $this->setAutoExit(false);
    }

    #[\Override]
    public function all(string $namespace = null): array
    {
        $commandsToRemove = [
            'about',
            'run',
            'assets:install',
            'router:match',
            'error:dump',
        ];
        if (!$this->getKernel()->isDebug()) {
            $commandsToRemove = array_merge($commandsToRemove, [
                'cache:clear',
                'cache:pool:clear',
                'cache:pool:delete',
                'cache:pool:invalidate-tags',
                'cache:pool:list',
                'cache:pool:prune',
                'cache:warmup',
                'config:dump-reference',
                'debug:autowiring',
                'debug:config',
                'debug:container',
                'debug:dotenv',
                'debug:event-dispatcher',
                'debug:router',
                'debug:serializer',
                'lint:container',
                'lint:yaml',
                'router:match',
                'secrets:decrypt-to-local',
                'secrets:encrypt-from-local',
                'secrets:generate-keys',
                'secrets:list',
                'secrets:remove',
                'secrets:reveal',
                'secrets:set',
            ]);
        }

        return array_filter(
            parent::all($namespace),
            fn($name) => !in_array($name, $commandsToRemove, true),
            ARRAY_FILTER_USE_KEY
        );
    }

    #[\Override]
    protected function getDefaultInputDefinition(): InputDefinition
    {
        $definition = parent::getDefaultInputDefinition();
        $definition->setOptions([]);

        return $definition;
    }
}
