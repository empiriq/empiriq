Ticker Bundle
===

TickerBundle provides a unified **tick generation mechanism** for Symfony applications. It emits `TickEvent`s into the Symfony EventDispatcher using one of two interchangeable clock strategies:

* **Time-driven** — ticks are produced at a fixed time interval (real-time)
* **Event-driven** — ticks are produced in response to incoming events (backtest)

---

## Core Concepts

### Tick

A **tick** represents a discrete, period-based moment in time that drives
time-dependent logic in the system.

Ticks are delivered as **named Symfony events** using a single shared payload
class:

- `TickEvent`

#### Period-based subscriptions

Consumers subscribe to ticks **by event name**, passing intervals as query
parameters to keep the dot-style event prefix intact:

```
ticker.interval?seconds=1.5
```

The `seconds` parameter is a numeric interval in seconds (integer or float).

If you need multiple intervals, register multiple event subscriptions.

The system automatically **detects which tick intervals are subscribed to**
and dispatches tick events only for those intervals.

No explicit timer or scheduler configuration is required — declaring a
subscription is sufficient.

This design allows multiple independent tick streams to coexist efficiently
while keeping the event model explicit, deterministic, and introspectable at
compile time.

---

## Clock Abstraction

At the heart of the bundle is a single abstraction:

```
ClockInterface
```

It defines a controllable source of ticks. Two implementations are provided.

### TimeDrivenClock

* Produces ticks at a fixed interval
* Intended for **real-time** execution
* Implemented using `React\\EventLoop\\Loop::addPeriodicTimer`
* Independent of incoming data

Typical use cases:

* background workers
* daemons
* real-time monitoring
* periodic maintenance tasks

### EventDrivenClock

* Produces ticks **only when input events occur**
* Intended for **simulation, replay, and backtesting**
* Advances time based on event timestamps
* No sleeping or wall-clock dependency

Typical use cases:

* backtesting
* historical data replay
* deterministic simulations

---

## Configuration

The bundle is configured using a single option that selects the clock type.

```yaml
# config/packages/ticker.yaml
ticker:
  clock: time   # or "event"
```

### Available values

| Value   | Clock implementation |
| ------- | -------------------- |
| `time`  | `TimeDrivenClock`    |
| `event` | `EventDrivenClock`   |

The selected clock is registered as the active `ClockInterface` service.

---

## Events

### TickEvent

Dispatched on every tick.

```php
final readonly class TickEvent
{
    public function __construct(
        public \DateTimeImmutable $time,
    ) {
    }
}
```

---

## Listening to Ticks

```php
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use TickerBundle\TickEvent;

final class ExampleSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            'ticker.interval?seconds=1' => 'onTick',
        ];
    }

    public function onTick(TickEvent $event): void
    {
        var_dump('tick');
    }
}
```
