# 0019. The publish delay is measured from live trades

**Status:** Proposed. Refines [0010](0010-declarative-history.md).

## Context

Historical trades have a trade time, but no publish time. A backfiller sets
`event_time = trade_time + publish_delay`. `event_time` decides when a frame
sees a trade.

Until now, the publish delay was an argument of each history request. This
had three problems:

- The delay is a property of the venue, not of a request. The operator had
  to guess it.
- Two requests for the same period with different delays gave rows whose
  `event_time` depended on which run loaded first.
- Strategies on the same instrument saw history differently, depending on
  who requested it.

The live feeder stores both times of the venue: when the trade happened and
when the venue published it. So the real delay can be measured.

## Decision

- `market.publish_delay` keeps one measured delay for each venue and stream:
  the 99th percentile of `event_time - trade_time` of live trades.
- The sample is the newest live trades of the venue (writers of feeders), at
  most 10000 for each instrument, in the last 7 days. A measurement needs at
  least 1000 trades. Both times come from the venue, so the local clock does
  not matter.
- `api.lease_history()` and `api.start_backfill()` use the measured delay. If
  it is older than one hour, they measure again first.
- Without a measurement (no live trades yet, or depth, which has no trade
  time) the default of the backfiller applies: the setting `publish_delay`
  of `api.register_backfiller()`, else 0.
- Each backfill run records the delay that it used
  (`market.backfill_run.publish_delay`).
- History requests have no publish delay any more.
- `api.publish_delay` shows the measurements. `api.measure_publish_delay()`
  measures a venue now.

## Consequences

- History gets the delay that the venue really has. For Binance it is
  usually a few milliseconds, not a guessed 50 ms.
- A delay for a slower reaction of a strategy does not belong into the
  data. It belongs into the simulated reconciler of a backtest (TBD).
- Runs of one stream can have different delays, if the measurement changed
  between them. Each run records its delay, and frames stay repeatable through
  their cutoffs.
- A venue needs live trades before its history gets a measured delay. Start
  the feeder first, or set the default of the backfiller.
- The delay of today is used for history of any age. The real delay of past
  years is not known.
- `api.add_history_request()` loses `p_publish_delay`. This is a breaking
  change of `api`.
