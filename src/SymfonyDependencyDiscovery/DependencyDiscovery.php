<?php

namespace Empiriq\SymfonyDependencyDiscovery;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

final class DependencyDiscovery
{
    /**
     * Collects all injected dependencies referenced in service definitions.
     *
     * @return string[] Dependency identifiers (service ids and/or class names).
     */
    public function discover(ContainerBuilder $container): array
    {
        $dependencies = [];
        foreach ($container->getDefinitions() as $definition) {
            $this->collectFromArguments($definition->getArguments(), $dependencies);
            $this->collectFromConstructor($definition, $dependencies);
        }

        $dependencies = array_values(array_unique($dependencies));
        sort($dependencies);

        return $dependencies;
    }

    /**
     * @param array<mixed> $arguments
     * @param array<int, string> $dependencies
     */
    private function collectFromArguments(array $arguments, array &$dependencies): void
    {
        foreach ($arguments as $argument) {
            if (is_array($argument)) {
                $this->collectFromArguments($argument, $dependencies);
                continue;
            }
            if ($argument instanceof Reference) {
                $dependencies[] = (string) $argument;
                continue;
            }
            if ($argument instanceof Definition) {
                $class = $argument->getClass();
                if (is_string($class) && $class !== '') {
                    $dependencies[] = $class;
                }
                $this->collectFromArguments($argument->getArguments(), $dependencies);
            }
        }
    }

    /**
     * @param array<int, string> $dependencies
     */
    private function collectFromConstructor(Definition $definition, array &$dependencies): void
    {
        $class = $definition->getClass();
        if (!is_string($class) || $class === '' || !class_exists($class)) {
            return;
        }

        $reflection = new \ReflectionClass($class);
        $constructor = $reflection->getConstructor();
        if ($constructor === null) {
            return;
        }

        foreach ($constructor->getParameters() as $parameter) {
            $type = $parameter->getType();
            if ($type === null) {
                continue;
            }

            foreach ($this->extractTypeNames($type, $reflection) as $typeName) {
                $dependencies[] = $typeName;
            }
        }
    }

    /**
     * Extracts class names from a reflection type.
     *
     * Known limitations (future improvements):
     * - Factory services: when a definition uses a factory, the actual
     *   constructed class may differ from Definition::getClass().
     * - Autowire binds: arguments injected via container "bind" are not
     *   represented in Definition::getArguments(), so they are missed here.
     *
     * @return string[]
     */
    private function extractTypeNames(\ReflectionType $type, \ReflectionClass $context): array
    {
        if ($type instanceof \ReflectionNamedType) {
            return $this->normalizeNamedType($type, $context);
        }

        if ($type instanceof \ReflectionUnionType) {
            $names = [];
            foreach ($type->getTypes() as $innerType) {
                $names = array_merge($names, $this->normalizeNamedType($innerType, $context));
            }

            return $names;
        }

        if ($type instanceof \ReflectionIntersectionType) {
            $names = [];
            foreach ($type->getTypes() as $innerType) {
                $names = array_merge($names, $this->normalizeNamedType($innerType, $context));
            }

            return $names;
        }

        return [];
    }

    /**
     * Normalizes a named type into a concrete class name.
     *
     * @return string[]
     */
    private function normalizeNamedType(\ReflectionNamedType $type, \ReflectionClass $context): array
    {
        if ($type->isBuiltin()) {
            return [];
        }

        $name = $type->getName();
        if ($name === 'self') {
            return [$context->getName()];
        }
        if ($name === 'parent') {
            $parent = $context->getParentClass();
            return $parent ? [$parent->getName()] : [];
        }

        return [$name];
    }
}
