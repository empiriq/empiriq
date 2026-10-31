set client_min_messages = warning;

-- Database logins of the services. Each login is a member of one role of
-- empiriq (empiriq ADR 0001). Run with psql variables:
--   console_user, console_password, backfiller_user, backfiller_password,
--   feeder_user, feeder_password
-- Safe to run again: it creates missing logins and sets the passwords.

select format('create role %I login', :'console_user')
 where not exists (select from pg_roles where rolname = :'console_user') \gexec
select format('alter role %I password %L', :'console_user', :'console_password') \gexec
select format('grant empiriq_operator to %I', :'console_user') \gexec

select format('create role %I login', :'backfiller_user')
 where not exists (select from pg_roles where rolname = :'backfiller_user') \gexec
select format('alter role %I password %L', :'backfiller_user', :'backfiller_password') \gexec
select format('grant empiriq_feeder to %I', :'backfiller_user') \gexec

select format('create role %I login', :'feeder_user')
 where not exists (select from pg_roles where rolname = :'feeder_user') \gexec
select format('alter role %I password %L', :'feeder_user', :'feeder_password') \gexec
select format('grant empiriq_feeder to %I', :'feeder_user') \gexec
