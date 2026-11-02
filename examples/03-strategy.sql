-- 03. A strategy on the history of yesterday.
--
--   make sql F=examples/03-strategy.sql
--
-- Load the history first (02-history.sql). This file runs the example
-- strategy strategies.sma_cross (a moving average crossover) once for every
-- hour of yesterday, and shows the position that it wants after each run.
-- Nothing is sent to Binance: the account is in the paper mode, and there is
-- no reconciler yet.

-- An account. empiriq has no api function for accounts yet, so the owner of
-- the database inserts it.
insert into core.account (venue_id, name, mode)
values ('binance-futures-um', 'demo', 'paper')
on conflict (name) do nothing;

-- The strategy: it wants 0.01 BTC while the average of the last 5 closes of
-- 1-minute bars is above the average of the last 20, else nothing.
select api.register_strategy('sma-demo', 'demo', 'strategies.sma_cross',
         jsonb_build_object('instrument_id', api.instrument_id('binance-futures-um', 'BTCUSDT'),
                            'fast', 5, 'slow', 20, 'size', 0.01, 'interval', '1 minute'));
select api.subscribe('sma-demo', api.instrument_id('binance-futures-um', 'BTCUSDT'), 'trade');

-- A reconciler calls api.reconciled() after each check of the account, and
-- empiriq runs a frame of every running strategy of the account. Here we call
-- it by hand, with as_of in the past: once for each hour of yesterday. Hours
-- that already have a frame are skipped, so you can run this file again.
select count(api.reconciled(a.id, t)) as frames
  from api.account a,
       date_trunc('day', now(), 'UTC') d,          -- today, 00:00 UTC
       generate_series(d - interval '23 hours', d, interval '1 hour') t
 where a.name = 'demo'
   and t > coalesce((select max(f.as_of) from api.frame f where f.account_id = a.id), '-infinity');

-- The frames: when they ran, and how many intents the strategy returned.
select as_of, status, intents, error
  from api.frame
 where strategy = 'sma-demo'
 order by as_of;

-- The desired position after the last frame. qty is empty: no reconciler
-- reports the real position of the paper account yet.
select instrument_id, target, qty, strategy
  from api.position
 where account_id = (select id from api.account where name = 'demo');
