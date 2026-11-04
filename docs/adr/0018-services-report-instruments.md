# 0018. Feeders and backfillers report instruments

**Status:** Proposed

**Related:**

- Refines [0012. Backfiller registry: services report, work is controlled](0012-backfiller-registry.md).
- Refines [0016. Live requests: feeders register, operators declare live streams](0016-live-requests.md).

## Context

An operator had to register every instrument with
`api.register_instrument()` before a live request or a history request could
use it. The venue already has the list: Binance publishes it in
`exchangeInfo`. A list that a person types in is incomplete, and it becomes
outdated when the venue adds or removes instruments.

ADR 0012 says that services report what they observe. The instruments of a
venue are observed state.

## Decision

- `api.report_instruments(venue_id, instruments)` takes the complete list of
  instruments of one venue: symbol, kind, base asset, quote asset, settle
  asset and whether the instrument trades now.
- It creates missing venues, assets and instruments, and updates changed
  ones. An instrument that is not in the list any more becomes inactive.
- An empty list is an error, so that a failed request to the venue cannot
  make all instruments inactive.
- Feeders and backfillers call it for each of their venues at start, and then
  every hour. Both have the role `empiriq_feeder`.
- An instrument keeps its id forever. A delisted instrument stays, with its
  market data and history.
- The operator cannot register instruments. The board shows them
  read-only. The owner can still call `api.register_instrument()` for a venue
  without a service, for example a paper venue.

## Consequences

- After the first start of a feeder or a backfiller, all instruments of its
  venues exist. An operator only chooses one for a live request or a history
  request.
- Two services can report the same venue. The reports are the same list of
  the venue, so their order does not matter.
- An instrument that the venue removed before the first report never appears.
  History of such an instrument needs `api.register_instrument()` by the
  owner.
- Each service needs read access to the instrument list of its venue. For
  Binance this is a public endpoint.
