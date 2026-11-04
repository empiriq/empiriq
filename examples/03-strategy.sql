-- 03. A strategy on the history of yesterday.
--
--   make sql F=examples/03-strategy.sql
--
-- Load the history first (02-history.sql). This file runs the example
-- strategy strategies.sma_cross (a moving average crossover) once for every
-- hour of yesterday, and shows the position that it wants after each run.
-- Nothing is sent to Binance: the account is in a replay world, and there is
-- no reconciler yet.

-- A replay world with one frame for each hour of yesterday (ADR 0022), and
-- its account. empiriq has no api function for worlds and accounts yet, so
-- the owner of the database inserts them.
insert into core.world (name, clock, step, replay_to)
values ('demo', 'replay', '1 hour', date_trunc('day', now(), 'UTC'))
on conflict (name) do nothing;
insert into core.account (venue_id, name, world_id)
values ('binance-futures-um', 'demo', (select id from core.world where name = 'demo'))
on conflict (name) do nothing;

-- The strategy: it wants 0.01 BTC while the average of the last 5 closes of
-- 1-minute bars is above the average of the last 20, else nothing.
select api.register_strategy('sma-demo', 'demo', 'strategies.sma_cross',
         jsonb_build_object('instrument_id', api.instrument_id('binance-futures-um', 'BTCUSDT'),
                            'fast', 5, 'slow', 20, 'size', 0.01, 'interval', '1 minute'));
select api.subscribe('sma-demo', api.instrument_id('binance-futures-um', 'BTCUSDT'), 'trade');

-- A reconciler calls api.reconciled() for each frame of its world, and the
-- last reconciliation ends the frame: the strategies run, and the next frame
-- opens. Here we play the reconciler: we start the world at 01:00 yesterday
-- and reconcile its frames until the world reaches replay_to. A world that
-- ran already is not started again, so you can run this file again.
do $$
declare
  v_world bigint := (select id from api.world where name = 'demo');
  v_frame bigint;
begin
  if (select last_as_of from api.world where id = v_world) is not null then
    return;
  end if;
  v_frame := api.start_world(v_world, (select replay_to - interval '23 hours' from api.world where id = v_world));
  while v_frame is not null loop
    perform api.reconciled((select id from api.account where name = 'demo'), v_frame);
    v_frame := (select open_frame_id from api.world where id = v_world);
  end loop;
end $$;

-- The strategy runs: at which as_of, and how many intents the strategy returned.
select as_of, status, intents, error
  from api.strategy_run
 where strategy = 'sma-demo'
 order by as_of;

-- The desired position after the last frame. qty is empty: no reconciler
-- reports the real position of the paper account yet.
select instrument_id, target, qty, strategy
  from api.position
 where account_id = (select id from api.account where name = 'demo');
