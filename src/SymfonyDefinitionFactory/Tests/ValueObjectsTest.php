<?php

namespace Empiriq\SymfonyDefinitionFactory\Tests;

use Empiriq\SymfonyDefinitionFactory\DefinitionSpec;
use Empiriq\SymfonyDefinitionFactory\ParsedSpec;
use PHPUnit\Framework\TestCase;

final class ValueObjectsTest extends TestCase
{
    public function testDefinitionSpecStoresValues(): void
    {
        $spec = new DefinitionSpec(\stdClass::class, ['a', 'b'], ['a']);

        self::assertSame(\stdClass::class, $spec->class);
        self::assertSame(['a', 'b'], $spec->args);
        self::assertSame(['a'], $spec->required);
    }

    public function testParsedSpecStoresValues(): void
    {
        $parsed = new ParsedSpec('hmac', ['secret' => 'x']);

        self::assertSame('hmac', $parsed->type);
        self::assertSame(['secret' => 'x'], $parsed->params);
    }
}
