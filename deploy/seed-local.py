#!/usr/bin/env python3
"""Initialize missing content files in a local checkout, preserving existing edits."""
import json
from pathlib import Path
from remote import seed_content

ROOT = Path(__file__).resolve().parents[1]
policy = json.loads((ROOT / 'deploy/policy.json').read_text())
seeds = {}
for path in (ROOT / 'deploy/defaults').rglob('*'):
    if path.is_file():
        target = path.relative_to(ROOT / 'deploy/defaults').as_posix()
        if '/' not in target or target.split('/')[0] in ['include', *policy['page_roots']]:
            seeds[target] = {'legacy': policy['legacy_content'].get(target)}
seed_content(ROOT, ROOT / 'deploy', {'seeds': seeds}, {})
print('Missing content files initialized. Existing values preserved.')
