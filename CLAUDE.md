# Instructions for Claude

This repository is the entry point of empiriq. It has the documentation of
the project (`docs/`), builds the images of all repositories
(`docker-bake.hcl`) and starts them together (`compose.yaml`). It has no code
of its own. The services live in their own repositories next to this one.

`README.md` is the entry point of the documentation: what empiriq is, a
quickstart and links. Details go into `docs/`, by the kind of document
(`docs/README.md`). Directories of this repository have no README of their
own.

- Each service repository has one Dockerfile and knows nothing about this
  repository. Compose uses only images: no `build:` and no mounts of
  service repositories.
- Configuration of a service that is the same everywhere belongs into the
  repository of the service (its Dockerfile). This repository sets only what
  depends on the place: paths, ports, passwords.
- Containers take their environment only from `env/*.env` (`env_file`),
  never from `environment:` in `compose.yaml`. Values that depend on the
  place come from `.env` as `${VAR}`. See `docs/reference/stack.md`.
- Names in `.env`: `IMAGE_*` for all images, `<SERVICE>_*` for one service,
  with its name in `compose.yaml` (`DATABASE_PORT`, `LOG_STORE_RETENTION`).
  The names of the images (`POSTGRES_*`, `GF_*`, `EMPIRIQ_DATABASE_*`) appear
  only in `env/`.
- All services take the database as the same variables
  `EMPIRIQ_DATABASE_HOST`, `_PORT`, `_NAME`, `_USER`, `_PASSWORD`
  (`env/database-client.env`). All log in as the owner. Feeders and
  backfillers add `EMPIRIQ_DATABASE_ROLE=empiriq_feeder`, so the stack checks
  the grants of `api`. Logins for each service belong to a real installation,
  not to this repository.

## Ubiquitous language

The rules for language and writing style apply to all repositories of
empiriq.

[docs/reference/glossary.md](docs/reference/glossary.md) is the vocabulary of
the project.

- Use its terms exactly, in docs, SQL, Go, comments and commit messages.
- Do not use the synonyms it lists.
- A new concept goes into the glossary first, in the same change.
- If code and glossary disagree, point it out instead of picking one silently.
- Terms listed under "Open decisions" need the qualified form until decided.

## Documentation

- Explanation and reference describe the current state. If you change code,
  update them in place, in the same change. Never add a document that only
  corrects another one.
- An ADR records why: the options and the choice. Write one only if options
  were weighed. Never edit an accepted ADR: add a new one
  ([docs/adr/README.md](docs/adr/README.md)).
- Do not put names of tables, columns or functions into an ADR. Use the
  terms of the glossary.

## Writing style

Write for readers whose first language is not English: docs, `COMMENT ON`,
error messages and commit messages. These rules serve clarity. If a rule makes
a text less clear, follow clarity.

1. One idea per sentence, about 20 words at most.
2. Active voice. Imperative in instructions. Condition first:
   "If the venue rejects the order, report `rejected`."
3. Use the terms from the glossary. One term per concept.
4. Plain words and common technical terms (TBD, PR, deprecated) are fine.
   Avoid idioms whose meaning does not follow from the words ("happy path",
   "under the hood").
5. Expand an abbreviation on first use if a reader may not know it:
   "high-frequency trading (HFT)".
6. If "it" or "this" could mean two things, repeat the noun.
7. Give units and time zones: "30 seconds", "00:10 UTC".
8. Main point first. Short paragraphs. Lists for 3+ items, numbered lists for steps.
9. Code names in backticks, exactly as in code.
10. Link to a definition instead of explaining it again.

When you edit text, apply these rules only to the parts you touch.

## Commit messages

After editing or adding files, end the summary with a ready-to-copy commit
message in [Conventional Commits](https://www.conventionalcommits.org/) format,
in a separate code block.

- Types: `feat`, `fix`, `docs`, `refactor`, `test`, `chore`, `perf`, `build`, `ci`.
- Scopes for this repo: `compose`, `bake`, `make`, `docs`, `adr`.
- Subject in English, imperative mood, no trailing period, max 72 characters.
