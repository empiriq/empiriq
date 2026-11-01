# Instructions for Claude

This repository builds the images of all repositories (`docker-bake.hcl`)
and starts them together (`compose.yaml`). It has no code of its own. The
services live in their own repositories next to this one.

- Each service repository has one Dockerfile and knows nothing about this
  repository. Compose uses only images: no `build:` and no mounts of
  service repositories.
- Use the terms of `../empiriq/docs/reference/glossary.md`.
- Follow the writing style of `../empiriq/CLAUDE.md` for docs and comments.
- Configuration of a service that is the same everywhere belongs into the
  repository of the service (its Dockerfile). This repository sets only what
  depends on the place: paths, ports, passwords.
- All services log in as `POSTGRES_USER`. Feeders and backfillers add
  `options=-c role=empiriq_feeder` to their URL, so the stack checks the
  grants of `api`. Logins for each service belong to a real installation,
  not to this repository.

## Commit messages

After editing or adding files, end the summary with a ready-to-copy commit
message in [Conventional Commits](https://www.conventionalcommits.org/) format,
in a separate code block.

- Types: `feat`, `fix`, `docs`, `refactor`, `test`, `chore`, `perf`, `build`, `ci`.
- Scopes for this repo: `compose`, `bake`, `make`, `docs`.
- Subject in English, imperative mood, no trailing period, max 72 characters.
