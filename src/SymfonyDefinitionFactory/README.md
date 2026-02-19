# Symfony Definition Factory

A tiny utility for building Symfony DI `Definition` objects from a single string specification.

The goal is to describe a service in one line, then materialize it into a `Definition` during
container compilation (e.g., in a compiler pass).

---

## Spec Format

```
type?key=value&key=value
```

- `type` is the spec identifier (lowercased).
- Parameters are parsed as raw strings (no URL-decoding), which makes `%env(...)%` safe.

Example:

```
hmac?api_key=%env(BINANCE_API_KEY)%&secret=%env(BINANCE_SECRET_KEY)%
```

---

## Basic Usage

```php
use Empiriq\SymfonyDefinitionFactory\DefinitionFactory;
use Empiriq\SymfonyDefinitionFactory\DefinitionSpec;
use Empiriq\SymfonyDefinitionFactory\QueryStringSpecParser;

$factory = new DefinitionFactory(
    new QueryStringSpecParser(),
    [
        'hmac' => new DefinitionSpec(\App\Security\HmacSigner::class, ['secret'], ['secret']),
        'unsigned' => new DefinitionSpec(\App\Security\NullSigner::class),
    ]
);

$definition = $factory->create('hmac?secret=topsecret');
```

---

## Override Parameters

Override values are merged over parsed parameters:

```php
$definition = $factory->create(
    'hmac?secret=ignored',
    ['secret' => '%env(APP_SECRET)%']
);
```

---

## Use Case Example (Auth)

```php
$parsed = $factory->parse('hmac?api_key=%env(API_KEY)%&secret=%env(SECRET)%');
$apiKey = $parsed->params['api_key'] ?? '';
$signerDefinition = $factory->createFromParsed($parsed);
```

---

## Notes

- Values are **not URL-decoded**.
- Unknown spec types throw a `RuntimeException`.
- Missing required parameters throw a `RuntimeException`.
