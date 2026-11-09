# Documentation

The documentation follows [Diátaxis](https://diataxis.fr/). Diátaxis has four
kinds of documents, one for each need of a reader.

| Section | Use it to | Contents |
|---------|-----------|----------|
| **Tutorials** (`tutorials/`) | Learn by doing | [Your first strategy](tutorials/first-strategy.md): start empiriq, load live and historical trades, run a strategy on history. *Planned:* a first backtest with a simulated reconciler |
| **How-to guides** (`how-to/`) | Do a specific task | [Run the stack](how-to/run-the-stack.md): start, change and reset the stack. *Planned:* write a feeder, write a backfiller, write a reconciler, run a backtest, add a venue, set up the board |
| **Reference** (`reference/`) | Look up facts | [Glossary](reference/glossary.md) (ubiquitous language), [Market data](reference/market-data.md) (contract for feeders and backfillers), [Stack](reference/stack.md) (services, images, settings, logs, make targets). *Planned:* reference of the `api` schema, generated from `COMMENT ON`; reconciler protocol; intent types |
| **Explanation** (`explanation/`) | Understand the design | [Architecture](explanation/architecture.md), [Frames, time and determinism](explanation/frames.md) |

Tutorials, how-to guides, reference and explanation describe empiriq as it
is now. Edit them in place with every change.

[ADRs](adr/README.md) record why empiriq works this way: the options and the
choice. They do not describe the current state. Start with
[ADR 0000](adr/0000-empiriq.md). The [ADRs by part](adr/README.md#by-part)
lead from there to the details.

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
  request. If it changes a contract, it also updates the documents here. If
  it changes a decision, it also adds an ADR
  ([when to write an ADR](adr/README.md#when-to-write-an-adr)).
- Follow the writing rules in [CLAUDE.md](../CLAUDE.md): short sentences,
  active voice, terms from the glossary.
