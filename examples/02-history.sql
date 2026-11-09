-- 02. History: ask the backfillers for the trades of yesterday.
--
--   make sql F=examples/02-history.sql
--
-- A history request declares which history is needed. The backfiller loads
-- what is missing from the Binance archive.
-- The archive publishes a day in the morning of the next day (UTC), so
-- yesterday is the newest day that is sure to exist.

-- One request for yesterday, 00:00 to 24:00 UTC. A second run of this file
-- adds nothing: the request exists already.
select api.add_history_request(api.instrument_id('binance-futures-um', 'BTCUSDT'), '{trade}',
                               d - interval '1 day', d)
  from date_trunc('day', now(), 'UTC') d          -- today, 00:00 UTC
 where not exists (select 1 from api.history_request
                    where venue_id = 'binance-futures-um' and symbol = 'BTCUSDT'
                      and period = tstzrange(d - interval '1 day', d));

-- The progress. The day is loaded when missing is empty. A day of BTCUSDT
-- has more than a million trades: allow a few minutes.
select venue_id, symbol, stream, covered, missing, running, failed
  from api.history_coverage;
