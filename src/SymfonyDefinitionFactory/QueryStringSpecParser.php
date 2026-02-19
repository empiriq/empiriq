<?php

namespace Empiriq\SymfonyDefinitionFactory;

/**
 * Parses "type?key=value&key=value" specifications without URL decoding.
 *
 * This parser keeps values intact (useful for "%env(...)%" placeholders).
 *
 * @api
 */
final class QueryStringSpecParser implements SpecParserInterface
{
    /**
     * @param string $spec Specification string.
     * @return ParsedSpec Parsed specification.
     */
    public function parse(string $spec): ParsedSpec
    {
        $spec = trim($spec);
        if ($spec === '') {
            throw new \RuntimeException('Spec must not be empty');
        }

        [$type, $query] = array_pad(explode('?', $spec, 2), 2, '');
        $type = strtolower(trim($type));
        if ($type === '') {
            throw new \RuntimeException('Spec type must not be empty');
        }

        $params = [];
        if ($query !== '') {
            foreach (explode('&', $query) as $pair) {
                if ($pair === '') {
                    continue;
                }
                [$key, $value] = array_pad(explode('=', $pair, 2), 2, '');
                if ($key === '') {
                    continue;
                }
                $params[$key] = $value;
            }
        }

        return new ParsedSpec($type, $params);
    }
}
