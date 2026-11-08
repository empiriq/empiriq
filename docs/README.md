# Documentation

The documentation follows [Diátaxis](https://diataxis.fr/). Diátaxis has four
kinds of documents, one for each need of a reader.

| Section | Use it to | Contents |
|---------|-----------|----------|
| **Tutorials** (`tutorials/`) | Learn by doing | [Your first strategy](tutorials/first-strategy.md): start empiriq, load live and historical trades, run a strategy on history. *Planned:* a first backtest with a simulated reconciler |
| **How-to guides** (`how-to/`) | Do a specific task | [Run the stack](how-to/run-the-stack.md): start, change and reset the stack. *Planned:* write a feeder, write a backfiller, write a reconciler, run a backtest, add a venue, set up the board |
| **Reference** (`reference/`) | Look up facts | [Glossary](reference/glossary.md) (ubiquitous language), [Market data](reference/market-data.md) (contract for feeders and backfillers), [Stack](reference/stack.md) (services, images, settings, logs, make targets). *Planned:* reference of the `api` schema, generated from `COMMENT ON`; reconciler protocol; intent types |
| **Explanation** (`explanation/`) | Understand the design | [Architecture](explanation/architecture.md) |

[ADRs](adr/README.md) record the decisions and their reasons. Start with
[ADR 0000](adr/0000-empiriq.md): it describes the whole project. The
[ADRs by part](adr/README.md#by-part) lead from there to the details.

## Conventions

- Write all documentation in English.
- The documentation of the project is here, in the `empiriq` repository: how
  empiriq works, the glossary, the contracts between services and the ADRs.
  Each service repository has a README for building and testing that
  service only.
- Every object in `api` has a `COMMENT ON`. The reference is generated from
  these comments, so the comments are the authoritative text. The reference
  and the changelog of `api` stay next to the schema, in the `database`
  repository.
- A change to `api` updates the reference and the changelog in the same pull
  request. If it changes a contract or a decision, it also updates the
  documents here.
- Follow the writing rules in [CLAUDE.md](../CLAUDE.md): short sentences,
  active voice, terms from the glossary.
- Run `make check-docs` before a pull request. It checks that the names of
  `api`, `market` and `core` exist in the database, that links work and that
  ADR numbers exist. A name that the code does not have yet, or no longer
  has, goes into [code-names.txt](code-names.txt).
