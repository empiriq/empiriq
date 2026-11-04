#!/usr/bin/env python3
"""Checks the docs of empiriq against the code.

    python3 scripts/check-docs.py --database ../database [--repo ../board ...]

1. Names: every `api.*`, `market.*` and `core.*` name in the docs, the
   Makefile and the examples exists in the migrations of the database. A name
   with a third part (`market.backfill_run.publish_delay`) needs that word in
   the definition of its object.
2. Links: relative links in Markdown point to files and headings that exist.
3. ADR numbers: "ADR 0009" in this repository and in the other repositories
   points to an ADR that exists.

Names that the code does not have on purpose are listed in docs/code-names.txt:
[planned] names may appear anywhere, [removed] names only in ADRs. A listed
name must not exist in the code. The script exits with 1 if it finds errors.
"""
import argparse
import glob
import os
import re
import sys

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
SCHEMAS = ('api', 'market', 'core')
NAME = re.compile(r'(?<![\w./-])(api|market|core)\.("[a-z_]+"|[a-z_][a-z0-9_]*\*?)(?:\.([a-z_][a-z0-9_]*))?')
NOT_NAMES = {'md', 'sql', 'go', 'json', 'yaml', 'yml', 'txt', 'py'}
CREATE = re.compile(
    r'^\s*create\s+(?:or\s+replace\s+)?(?:unique\s+)?'
    r'(?:table|view|materialized\s+view|function|procedure|type|sequence|index)\s+'
    r'(?:if\s+not\s+exists\s+)?((?:api|market|core)\.(?:"[a-z_]+"|[a-z_][a-z0-9_]*))',
    re.I | re.M)
ADR_REF = re.compile(r'\bADRs? (\d{4})\b')
URL = re.compile(r'https?://\S+')
LINK = re.compile(r'(?<!!)\[[^\]]*\]\(([^)\s]+)\)')

errors = []


def error(path, line, msg):
    errors.append(f'{os.path.relpath(path)}:{line}: {msg}')


def code_objects(database):
    """Objects of the migrations: name -> text of their definition."""
    objects = {}
    files = sorted(glob.glob(os.path.join(database, 'migrations', '*.sql')))
    if not files:
        sys.exit(f'check-docs: no migrations in {database}/migrations')
    for f in files:
        text = open(f, encoding='utf-8').read()
        starts = [(m.start(), m.group(1).lower()) for m in CREATE.finditer(text)]
        for i, (pos, name) in enumerate(starts):
            end = starts[i + 1][0] if i + 1 < len(starts) else len(text)
            objects[name] = objects.get(name, '') + text[pos:end]
    return objects


def read_list(path):
    """docs/code-names.txt: {'planned': {name: line}, 'removed': {name: line}}."""
    lists, section = {'planned': {}, 'removed': {}}, None
    if not os.path.exists(path):
        return lists
    for n, raw in enumerate(open(path, encoding='utf-8'), 1):
        line = raw.split('#', 1)[0].strip()
        if not line:
            continue
        m = re.fullmatch(r'\[(planned|removed)\]', line)
        if m:
            section = m.group(1)
        elif section:
            lists[section][line] = n
        else:
            error(path, n, 'name before [planned] or [removed]')
    return lists


def exists(name, objects):
    """name: 'api.x', 'api.x_*' or 'market.t.col'."""
    parts = name.split('.')
    obj = parts[0] + '.' + parts[1]
    if obj.endswith('*'):
        return any(o.startswith(obj[:-1]) for o in objects)
    if obj not in objects:
        return False
    if len(parts) == 3:
        return re.search(r'\b' + re.escape(parts[2]) + r'\b', objects[obj]) is not None
    return True


def doc_files():
    files = glob.glob(os.path.join(ROOT, 'docs', '**', '*.md'), recursive=True)
    files += [os.path.join(ROOT, f) for f in ('README.md', 'CLAUDE.md', 'Makefile')]
    files += glob.glob(os.path.join(ROOT, 'examples', '*.sql'))
    return [f for f in files if os.path.exists(f)]


def check_names(objects, lists):
    seen = set()
    planned, removed = lists['planned'], lists['removed']
    for f in doc_files():
        in_adr = os.path.join('docs', 'adr') in os.path.relpath(f, ROOT)
        for n, line in enumerate(open(f, encoding='utf-8'), 1):
            for m in NAME.finditer(URL.sub('', line)):
                if m.group(2) in NOT_NAMES:
                    continue
                name = '.'.join(p for p in m.groups() if p)
                obj = m.group(1) + '.' + m.group(2)
                seen.add(name)
                seen.add(obj)
                if name in removed or obj in removed:
                    if not in_adr:
                        error(f, n, f'{name} is removed from the code: mention it only in ADRs')
                    continue
                if name in planned or obj in planned:
                    continue
                if not exists(name, objects):
                    error(f, n, f'{name} is not in the code (add it to docs/code-names.txt if it is planned)')
    path = os.path.join(ROOT, 'docs', 'code-names.txt')
    for section, names in lists.items():
        for name, n in names.items():
            if exists(name, objects):
                error(path, n, f'{name} is in the code now: remove it from [{section}]')
            elif name not in seen:
                error(path, n, f'{name} is not in the docs any more: remove it from [{section}]')


def slug(heading):
    s = re.sub(r'[`*_]', '', heading.strip().lower())
    s = re.sub(r'\[([^\]]*)\]\([^)]*\)', r'\1', s)
    s = re.sub(r'[^\w\- ]', '', s)
    return s.replace(' ', '-')


def anchors(path):
    result, counts, fence = set(), {}, False
    for line in open(path, encoding='utf-8'):
        if line.startswith('```'):
            fence = not fence
        m = None if fence else re.match(r'#{1,6} (.+)', line)
        if m:
            s = slug(m.group(1))
            k = counts.get(s, 0)
            counts[s] = k + 1
            result.add(s if k == 0 else f'{s}-{k}')
    return result


def check_links():
    for f in glob.glob(os.path.join(ROOT, '**', '*.md'), recursive=True):
        fence = False
        for n, line in enumerate(open(f, encoding='utf-8'), 1):
            if line.startswith('```'):
                fence = not fence
            if fence:
                continue
            for target in LINK.findall(line):
                if re.match(r'[a-z]+:', target):
                    continue
                path, _, anchor = target.partition('#')
                full = os.path.normpath(os.path.join(os.path.dirname(f), path)) if path else f
                if not os.path.exists(full):
                    error(f, n, f'link to a missing file: {target}')
                elif anchor and full.endswith('.md') and anchor not in anchors(full):
                    error(f, n, f'link to a missing heading: {target}')


def check_adr_refs(repos):
    adrs = {os.path.basename(p)[:4] for p in glob.glob(os.path.join(ROOT, 'docs', 'adr', '[0-9]*.md'))}
    patterns = ('*.md', '*.sql', '*.go', '*.py', '*.json', '*.yaml', '*.yml', '*.hcl', 'Makefile', 'Dockerfile')
    for repo in [ROOT] + repos:
        for dirpath, dirnames, filenames in os.walk(repo):
            dirnames[:] = [d for d in dirnames if not d.startswith('.') and d != 'node_modules']
            for name in filenames:
                if not any(glob.fnmatch.fnmatch(name, p) for p in patterns):
                    continue
                f = os.path.join(dirpath, name)
                try:
                    lines = open(f, encoding='utf-8').readlines()
                except (UnicodeDecodeError, OSError):
                    continue
                for n, line in enumerate(lines, 1):
                    for num in ADR_REF.findall(line):
                        if num not in adrs:
                            error(f, n, f'ADR {num} does not exist')


def main():
    p = argparse.ArgumentParser(description='Check the docs against the code.')
    p.add_argument('--database', default=os.path.join(ROOT, '..', 'database'))
    p.add_argument('--repo', action='append', default=[], help='another repository to check for ADR numbers')
    a = p.parse_args()
    objects = code_objects(a.database)
    lists = read_list(os.path.join(ROOT, 'docs', 'code-names.txt'))
    check_names(objects, lists)
    check_links()
    check_adr_refs([a.database] + a.repo)
    for e in errors:
        print(e)
    print(f'check-docs: {len(errors)} errors' if errors else 'check-docs: ok')
    return 1 if errors else 0


if __name__ == '__main__':
    sys.exit(main())
