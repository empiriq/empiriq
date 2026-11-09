-- 01. Live trades: ask the feeders for a live stream.
--
--   make sql F=examples/01-live-trades.sql
--
-- The feeder writes the trades of every active live request.
-- The instrument comes from the feeder:
-- it reports all instruments of its venues at start.

-- Write the trades of BTCUSDT on Binance USDⓈ-M futures all the time.
-- If the request exists already, resume it instead.
select api.add_live_request(api.instrument_id('binance-futures-um', 'BTCUSDT'), '{trade}')
 where not exists (select 1 from api.live_request
                    where venue_id = 'binance-futures-um' and symbol = 'BTCUSDT' and stream = 'trade');

-- The request and the feeders that write it. After a few seconds, lag is
-- small and complete_until moves.
select id, venue_id, symbol, stream, desired_status, feeders, complete_until, lag
  from api.live_request;

-- The newest trades. Run this query again to see new rows.
select trade_time, price, qty, aggressor
  from api.trades(api.instrument_id('binance-futures-um', 'BTCUSDT'),
                  now() - interval '1 minute', now(), now())
 order by trade_time desc
 limit 10;
