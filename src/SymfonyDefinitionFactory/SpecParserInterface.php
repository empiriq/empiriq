<?php

namespace Empiriq\SymfonyDefinitionFactory;

/**
 * Parses a string specification into a {@see ParsedSpec}.
 *
 * @api
 */
interface SpecParserInterface
{
    /**
     * Parse a spec string.
     *
     * @param string $spec Specification string (e.g. "hmac?secret=...").
     * @return ParsedSpec Parsed specification.
     */
    public function parse(string $spec): ParsedSpec;
}
