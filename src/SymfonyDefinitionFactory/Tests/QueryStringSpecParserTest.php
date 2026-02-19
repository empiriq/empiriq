<?php

namespace Empiriq\SymfonyDefinitionFactory\Tests;

use Empiriq\SymfonyDefinitionFactory\QueryStringSpecParser;
use PHPUnit\Framework\TestCase;

final class QueryStringSpecParserTest extends TestCase
{
    public function testParsesTypeAndParams(): void
    {
        $parser = new QueryStringSpecParser();
        $spec = $parser->parse('hmac?secret=abc&api_key=abc');

        self::assertSame('hmac', $spec->type);
        self::assertSame('abc', $spec->params['secret']);
        self::assertSame('abc', $spec->params['api_key']);
    }

    public function testParsesTypeWithoutParams(): void
    {
        $parser = new QueryStringSpecParser();
        $spec = $parser->parse('unsigned');

        self::assertSame('unsigned', $spec->type);
        self::assertSame([], $spec->params);
    }

    public function testParsesEmptyValue(): void
    {
        $parser = new QueryStringSpecParser();
        $spec = $parser->parse('rsa?passphrase=');

        self::assertSame('rsa', $spec->type);
        self::assertArrayHasKey('passphrase', $spec->params);
        self::assertSame('', $spec->params['passphrase']);
    }

    public function testRejectsEmptySpec(): void
    {
        $parser = new QueryStringSpecParser();

        $this->expectException(\RuntimeException::class);
        $parser->parse('   ');
    }
}
