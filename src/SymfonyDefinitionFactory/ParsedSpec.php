<?php

namespace Empiriq\SymfonyDefinitionFactory;

/**
 * Parsed specification in the form "type?key=value&key=value".
 *
 * @api
 */
final readonly class ParsedSpec
{
    /**
     * @param string $type Spec type (normalized to lowercase).
     * @param array<string, string> $params Parsed query parameters.
     */
    public function __construct(
        public string $type,
        public array $params = [],
    ) {
    }
}
