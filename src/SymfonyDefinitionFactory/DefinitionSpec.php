<?php

namespace Empiriq\SymfonyDefinitionFactory;

/**
 * Describes how to build a Symfony DI definition for a spec type.
 *
 * @api
 */
final readonly class DefinitionSpec
{
    /**
     * @param class-string $class Service class name.
     * @param string[] $args Ordered list of parameter keys to pass as constructor arguments.
     * @param string[] $required List of required parameter keys.
     */
    public function __construct(
        public string $class,
        public array $args = [],
        public array $required = [],
    ) {
    }
}
