<?php

namespace Empiriq\BinanceRealBundle\DependencyInjection\Compiler;

use Empiriq\BinanceRealBundle\Common\Signers\Ed25519Signer;
use Empiriq\BinanceRealBundle\Common\Signers\HmacSigner;
use Empiriq\BinanceRealBundle\Common\Signers\NullSigner;
use Empiriq\BinanceRealBundle\Common\Signers\RsaSigner;
use Empiriq\BinanceRealBundle\DependencyInjection\BinanceRealExtension;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\HttpFoundation\HeaderUtils;

final class SignerBuildPass implements CompilerPassInterface
{
    #[\Override]
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasParameter(BinanceRealExtension::PARAMETER_NAME)) {
            throw new \Exception('CONFIG not found');
        }
        /** @var array<string, mixed> $config */
        $config = $container->getParameter(BinanceRealExtension::PARAMETER_NAME);
        if (!is_string($config['auth'] ?? null)) {
            throw new \RuntimeException('Auth config must be a string');
        }
        /** @var array<string, class-string> $mapping */
        $mapping = [
            'hmac' => HmacSigner::class,
            'ed25519' => Ed25519Signer::class,
            'rsa' => RsaSigner::class,
            'unsigned' => NullSigner::class,
        ];
        $signerDefinition = $this->parse($mapping, $config['auth']);
        if (!$signerDefinition) {
            throw new \RuntimeException('Invalid auth config');
        }
        $container->setDefinition('empiriq.binance.signer', $signerDefinition);
    }

    /**
     * @param array<string, class-string> $mapping
     */
    private function parse(array $mapping, string $eventName): ?Definition
    {
        foreach ($mapping as $key => $class) {
            $path = parse_url($eventName, PHP_URL_PATH);
            if (!is_string($path) || $path !== $key) {
                continue;
            }
            $def = new Definition($class);
            $rc = new \ReflectionClass($class);
            $ctor = $rc->getConstructor();
            $params = $ctor?->getParameters() ?? [];
            $query = parse_url($eventName, PHP_URL_QUERY);
            if (is_string($query) && $query !== '') {
                $params2 = HeaderUtils::parseQuery($query);
                /* @var \ReflectionParameter $argument */
                foreach ($params as $i => $argument) {
                    $def->setArgument($i, $params2[$argument->name] ?? null);
                }
            }

            return $def;
        }

        return null;
    }
}
