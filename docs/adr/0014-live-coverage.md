# 0014. Live coverage: feeders report continuous periods

**Status:** Proposed

**Related:**

- Refines [0008. Frames: reconciliation drives strategies, cutoffs make them deterministic](0008-frames.md).
- Refines [0010. Declarative history: requests, coverage and leases](0010-declarative-history.md).
- Refined by [0016](0016-live-requests.md): live requests.

## Context

History coverage shows which periods backfill runs loaded. Nothing shows
which periods the feeders stored. `market.watermark` keeps only the latest
`complete_until` of each stream. If a feeder stops for an hour and starts
again, the watermark moves on, and the lost hour is invisible.

To find such periods, there are three options:

- **A.** Count trades for each minute and look for empty minutes. A quiet
  market also has empty minutes, and the count reads all trades.
- **B.** Look for gaps of source trade ids (`api.trade_gaps()`). This works
  only for trades of sources with continuous ids, and it reads all trades.
- **C.** Let each feeder say which periods it covered.

## Decision

Option C.

- `market.live_coverage` keeps, for each feeder (writer) and stream, the
  periods in which the feeder stored every event. Periods use `event_time`.
- `api.report_watermark(writer_id, instrument_id, stream, complete_until,
  since, max_jump)` maintains the table. Feeders do not write it directly.
- A report continues the last period of the writer in two cases:
  - `since` is at or before the end of that period;
  - `since` is null, and `complete_until` jumped by at most `max_jump`
    (default 1 minute).
- Otherwise the report starts a new period at `since`, or at
  `complete_until`.
- A feeder passes `since` after each connect and reconnect: the time since
  which it stored every event, for example the time when it connected to
  the stream of the venue.
- `api.stream_summary` shows, for each stream, the union of live coverage and
  history coverage, the uncovered periods between them, and the state of the
  live feed. It reads only metadata, never trades.
- `api.coverage_timeline` lists live and history periods for timelines.

## Consequences

- The board shows lost periods of live data without reading trades.
- The coverage is only as good as the feeder's reports. A feeder that loses
  events but reports a continuous `complete_until` hides the loss.
  `api.trade_gaps()` remains the check against the data.
- Live periods use `event_time`. History periods use `trade_time`. At the
  boundaries the two can differ by the latency of the venue.
- A feeder that reports often writes one row update for each report. This is
  the same cost as the update of `market.watermark` before.
