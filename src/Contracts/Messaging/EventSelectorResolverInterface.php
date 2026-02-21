<?php

namespace Empiriq\Contracts\Messaging;

/**
 * Resolves a published event into selector routing metadata.
 *
 * @api
 */
interface EventSelectorResolverInterface
{
    /**
     * @return array{path: string, selectors: array<string, string>}|null
     */
    public function resolve(object $event): ?array;
}
