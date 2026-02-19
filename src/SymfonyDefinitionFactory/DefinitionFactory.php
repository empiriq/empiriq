<?php

namespace Empiriq\SymfonyDefinitionFactory;

use Symfony\Component\DependencyInjection\Definition;

/**
 * Builds Symfony DI definitions from string specifications.
 *
 * @api
 */
final readonly class DefinitionFactory
{
    /**
     * @param SpecParserInterface $parser Spec parser implementation.
     * @param array<string, DefinitionSpec> $map Map of type => definition specification.
     */
    public function __construct(
        private SpecParserInterface $parser,
        private array $map
    ) {
    }

    /**
     * Parse a specification string.
     *
     * @param string $spec Specification string.
     * @return ParsedSpec Parsed specification.
     */
    public function parse(string $spec): ParsedSpec
    {
        return $this->parser->parse($spec);
    }

    /**
     * Build a definition from a specification string.
     *
     * @param string $spec Specification string.
     * @param array<string, mixed> $overrideParams Parameters that override parsed values.
     */
    public function create(string $spec, array $overrideParams = []): Definition
    {
        return $this->createFromParsed($this->parse($spec), $overrideParams);
    }

    /**
     * Build a definition from a parsed specification.
     *
     * @param ParsedSpec $spec Parsed specification.
     * @param array<string, mixed> $overrideParams Parameters that override parsed values.
     */
    public function createFromParsed(ParsedSpec $spec, array $overrideParams = []): Definition
    {
        $definitionSpec = $this->getDefinitionSpec($spec->type);
        $params = array_replace($spec->params, $overrideParams);
        $this->assertRequired($definitionSpec, $params);

        $args = [];
        foreach ($definitionSpec->args as $paramKey) {
            $args[] = $params[$paramKey] ?? null;
        }

        return new Definition($definitionSpec->class, $args);
    }

    /**
     * @param string $type Spec type.
     */
    private function getDefinitionSpec(string $type): DefinitionSpec
    {
        if (!isset($this->map[$type])) {
            throw new \RuntimeException(sprintf('Unsupported spec type "%s"', $type));
        }

        return $this->map[$type];
    }

    /**
     * @param DefinitionSpec $definitionSpec Definition specification.
     * @param array<string, mixed> $params Parameters to validate.
     */
    private function assertRequired(DefinitionSpec $definitionSpec, array $params): void
    {
        foreach ($definitionSpec->required as $required) {
            if (!array_key_exists($required, $params) || $params[$required] === '') {
                throw new \RuntimeException(sprintf('Missing required parameter "%s"', $required));
            }
        }
    }
}
