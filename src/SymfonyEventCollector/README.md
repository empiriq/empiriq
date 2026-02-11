# Symfony Event Collector

A lightweight design-time event introspection library for Symfony-based applications.

This library extracts a **catalog of events that listeners and subscribers declare they are able to handle**, based on class declarations — not runtime registration.

---

## Purpose

Symfony does not maintain a global event registry.  
An event is considered "registered" only when a listener or subscriber service exists at runtime.

This library addresses a different problem:

> **Which events does the codebase declare it can listen to?**

The result is a **potential / declared event catalog**, suitable for:
- design-time analysis
- configuration validation
- tooling and diagnostics
- event-driven infrastructure (tickers, schedulers, runtimes)

---

## Key Characteristics

- Design-time only (container build phase)
- No reliance on tags or compiler passes
- Class-based introspection
- Explicit separation between *declared* and *runtime* events

---

## Supported Sources

Out of the box, the library extracts events from:

- `EventSubscriberInterface::getSubscribedEvents()`
- `#[AsEventListener]` attributes

Additional extractors can be added via a simple interface.

---

## Basic Usage

```php
use Empiriq\SymfonyEventCollector\Collector;
use Empiriq\SymfonyEventCollector\Handler\Subscriber;
use Empiriq\SymfonyEventCollector\Handler\Listener;

$collector = new Collector([
    new Subscriber(),
    new Listener(),
]);

$events = $collector->collect($container);
```
