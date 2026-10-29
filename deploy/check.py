#!/usr/bin/env python3
"""Run the repository's PHP/JS regressions and deployment integration tests."""
from pathlib import Path
import subprocess
import sys

ROOT = Path(__file__).resolve().parents[1]
sys.path.insert(0, str(ROOT / 'deploy'))
from release import is_code
import json

policy = json.loads((ROOT / 'deploy/policy.json').read_text())
tracked = subprocess.check_output(['git', 'ls-files', '-z'], cwd=ROOT).decode().split('\0')
php = {ROOT / name for name in tracked if name.endswith('.php') and is_code(name, policy)}
php.update((ROOT / 'deploy').glob('*.php'))
for path in sorted(php):
    subprocess.run(['php', '-d', 'short_open_tag=1', '-l', str(path)], check=True, stdout=subprocess.DEVNULL)
print(f'PHP syntax: {len(php)} files', flush=True)
for path in sorted((ROOT / 'tests').glob('*.php')):
    subprocess.run(['php', '-d', 'short_open_tag=1', str(path)], cwd=ROOT, check=True)
subprocess.run(['node', 'tests/form-inline-scripts.mjs'], cwd=ROOT, check=True)
subprocess.run([sys.executable, '-m', 'unittest', 'discover', '-s', 'deploy/tests', '-v'], cwd=ROOT, check=True)
