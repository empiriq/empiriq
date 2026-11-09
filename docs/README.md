# Documentation

The documentation follows [Diátaxis](https://diataxis.fr/). Diátaxis has four
kinds of documents, one for each need of a reader.

| Section | Use it to | Contents |
|---------|-----------|----------|
| **Tutorials** (`tutorials/`) | Learn by doing | [Your first strategy](tutorials/first-strategy.md): start empiriq, load live and historical trades, run a strategy on history |
| **How-to guides** (`how-to/`) | Do a specific task | [Run the stack](how-to/run-the-stack.md): start, change and reset the stack |
| **Reference** (`reference/`) | Look up facts | [Glossary](reference/glossary.md) (ubiquitous language), [Market data](reference/market-data.md) (contract for feeders and backfillers), [Stack](reference/stack.md) (services, images, settings, logs, make targets) |
| **Explanation** (`explanation/`) | Understand the design | [Architecture](explanation/architecture.md), [Frames, time and determinism](explanation/frames.md) |

These documents describe empiriq as it is now. Edit them in place with
every change. They have no history: git has it. The goal and the reasons of
a change are in its issue in
[empiriq/empiriq](https://github.com/empiriq/empiriq/issues).

## Conventions

- Write all documentation in English.
- The documentation of the project is here, in the `empiriq` repository: how
  empiriq works, the glossary and the contracts between services.
  Each service repository has a README for building and testing that
  service only.
- Every object in `api` has a `COMMENT ON`. The reference is generated from
  these comments, so the comments are the authoritative text. The reference
  and the changelog of `api` stay next to the schema, in the `database`
  repository.
- A change to `api` updates the reference and the changelog in the same pull
  request. If it changes how empiriq works, it also updates the documents
  here.
- Follow the writing rules in [CLAUDE.md](../CLAUDE.md): short sentences,
  active voice, terms from the glossary.
