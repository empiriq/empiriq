<?php

namespace Empiriq\SymfonyDefinitionFactory\Tests;

use Empiriq\SymfonyDefinitionFactory\DefinitionFactory;
use Empiriq\SymfonyDefinitionFactory\DefinitionSpec;
use Empiriq\SymfonyDefinitionFactory\ParsedSpec;
use Empiriq\SymfonyDefinitionFactory\QueryStringSpecParser;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Definition;

final class DefinitionFactoryTest extends TestCase
{
    public function testCreatesDefinitionFromSpec(): void
    {
        $factory = new DefinitionFactory(
            new QueryStringSpecParser(),
            [
                'hmac' => new DefinitionSpec(\stdClass::class, ['secret'], ['secret']),
            ]
        );

        $definition = $factory->create('hmac?secret=topsecret');

        self::assertInstanceOf(Definition::class, $definition);
        self::assertSame(\stdClass::class, $definition->getClass());
        self::assertSame(['topsecret'], $definition->getArguments());
    }

    public function testCreatesDefinitionFromParsedSpec(): void
    {
        $factory = new DefinitionFactory(
            new QueryStringSpecParser(),
            [
                'unsigned' => new DefinitionSpec(\stdClass::class),
            ]
        );

        $definition = $factory->createFromParsed(new ParsedSpec('unsigned'));

        self::assertSame(\stdClass::class, $definition->getClass());
        self::assertSame([], $definition->getArguments());
    }

    public function testOverridesParams(): void
    {
        $factory = new DefinitionFactory(
            new QueryStringSpecParser(),
            [
                'hmac' => new DefinitionSpec(\stdClass::class, ['secret'], ['secret']),
            ]
        );

        $definition = $factory->create('hmac?secret=ignored', ['secret' => 'real']);

        self::assertSame(['real'], $definition->getArguments());
    }

    public function testThrowsOnMissingRequiredParam(): void
    {
        $factory = new DefinitionFactory(
            new QueryStringSpecParser(),
            [
                'hmac' => new DefinitionSpec(\stdClass::class, ['secret'], ['secret']),
            ]
        );

        $this->expectException(\RuntimeException::class);
        $factory->create('hmac');
    }

    public function testThrowsOnUnknownType(): void
    {
        $factory = new DefinitionFactory(new QueryStringSpecParser(), []);

        $this->expectException(\RuntimeException::class);
        $factory->create('unknown');
    }
}
