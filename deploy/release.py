#!/usr/bin/env python3
"""Build a release from tracked application code and freshly built Vite assets."""
import argparse
import hashlib
import json
from pathlib import Path
import subprocess
import tarfile
import tempfile

ROOT = Path(__file__).resolve().parents[1]
TEMPLATE = 'local/templates/littleweb/'


def digest(path):
    return hashlib.sha256(path.read_bytes()).hexdigest()


def is_code(name, policy):
    path = Path(name)
    if any(' copy' in part.lower() for part in path.parts):
        return False
    if path.name in ('.DS_Store', 'README.md', 'dbconn.php') or name.endswith('.map'):
        return False
    if name in policy['shared_files'] or name in policy['legacy_content'].values():
        return False
    if name.startswith(TEMPLATE + 'include/company-info/'):
        return False
    return (name in policy['code_files']
            or any(name == page + '/index.php' for page in policy['page_roots'])
            or any(name.startswith(base) for base in policy['code_roots']))


def build(output):
    policy = json.loads((ROOT / 'deploy/policy.json').read_text())
    tracked = subprocess.check_output(['git', 'ls-files', '-z'], cwd=ROOT).decode().split('\0')
    code = {name: ROOT / name for name in tracked if name and is_code(name, policy)}
    dist = ROOT / TEMPLATE / '_dist'
    if not (dist / 'manifest.json').is_file():
        raise RuntimeError('Run npm ci and npm run build before packaging')
    code.update({p.relative_to(ROOT).as_posix(): p for p in dist.rglob('*')
                 if p.is_file() and not p.name.endswith('.map')})
    seeds = {}
    for path in (ROOT / 'deploy/defaults').rglob('*'):
        if not path.is_file():
            continue
        target = path.relative_to(ROOT / 'deploy/defaults').as_posix()
        if '/' in target and target.split('/')[0] not in ['include', *policy['page_roots']]:
            continue
        seeds[target] = {'sha256': digest(path), 'legacy': policy['legacy_content'].get(target)}
    revision = subprocess.check_output(['git', 'rev-parse', 'HEAD'], cwd=ROOT).decode().strip()
    metadata = {'version': 1, 'revision': revision,
                'code': {name: digest(p) for name, p in sorted(code.items())}, 'seeds': seeds}
    for path in code.values():
        if path.is_symlink() or not path.is_file():
            raise RuntimeError('Code must consist of regular files: ' + str(path))
    output = Path(output).resolve()
    output.parent.mkdir(parents=True, exist_ok=True)
    with tempfile.TemporaryDirectory() as work:
        manifest = Path(work) / 'release.json'
        manifest.write_text(json.dumps(metadata, indent=2) + '\n')
        with tarfile.open(output, 'w:gz') as archive:
            archive.add(manifest, arcname='release.json')
            for name, path in sorted(code.items()):
                archive.add(path, arcname='code/' + name, recursive=False)
            for target in seeds:
                archive.add(ROOT / 'deploy/defaults' / target, arcname='defaults/' + target)
            for name in ['remote.py', 'backup.py', 'db-settings.php', 'clear-cache.php']:
                archive.add(ROOT / 'deploy' / name, arcname='ops/' + name)
    print(f'Release {revision}: {len(code)} code files, {len(seeds)} create-only defaults -> {output}')


if __name__ == '__main__':
    parser = argparse.ArgumentParser(description=__doc__)
    parser.add_argument('--output', required=True)
    build(parser.parse_args().output)
