#!/usr/bin/env python3
"""Apply or roll back a release. The state directory must be outside the web root."""
import argparse
from contextlib import contextmanager
from datetime import datetime, timezone
import fcntl
import grp
import hashlib
import json
import os
from pathlib import Path, PurePosixPath
import re
import shutil
import subprocess
import tarfile
import tempfile
import threading
import time
import urllib.parse
import urllib.request


def sha(path):
    return hashlib.sha256(path.read_bytes()).hexdigest()


def safe_path(root, name):
    parts = PurePosixPath(name)
    if not name or parts.is_absolute() or '..' in parts.parts or str(parts) != name or '\\' in name:
        raise RuntimeError('Unsafe relative path: ' + name)
    path = root / name
    for ancestor in [path, *path.parents]:
        if ancestor == root:
            break
        if ancestor.is_symlink():
            raise RuntimeError('Symlink inside managed path: ' + name)
    if path.exists() and not path.is_file():
        raise RuntimeError('Expected regular file: ' + name)
    return path


def atomic_bytes(path, data, mode=0o644):
    public_dirs(path.parent)
    handle, temporary = tempfile.mkstemp(prefix='.deploy-', dir=path.parent)
    try:
        with os.fdopen(handle, 'wb') as output:
            output.write(data)
            output.flush()
            os.fsync(output.fileno())
        os.chmod(temporary, mode)
        os.replace(temporary, path)
    finally:
        if os.path.exists(temporary):
            os.unlink(temporary)


def write_json(path, value):
    atomic_bytes(path, (json.dumps(value, indent=2) + '\n').encode(), 0o600)


def load_json(path, default=None):
    return json.loads(path.read_text()) if path.exists() else default


def unlink_if_exists(path):
    # pathlib.unlink(missing_ok=True) requires Python 3.8; Timeweb also runs 3.6.
    try:
        path.unlink()
    except FileNotFoundError:
        pass


def public_dirs(path, mode=0o755, group=None):
    missing = []
    current = path
    while not current.exists():
        missing.append(current)
        current = current.parent
    for directory in reversed(missing):
        directory.mkdir()
        if group is not None:
            os.chown(directory, -1, group)
        directory.chmod(mode)


def allowed_code(name):
    path = PurePosixPath(name)
    if path.name in ['.settings.php', '.settings_extra.php', 'dbconn.php', '.access.php', '.htaccess']:
        return False
    if path.name.endswith('.menu.php') or path.name == '.section.php' or name.startswith('include/'):
        return False
    if name.startswith(('local/ajax/', 'local/php_interface/', 'local/templates/littleweb/')):
        if name.startswith('local/templates/littleweb/include/'):
            relative = name[len('local/templates/littleweb/include/'):]
            if relative.startswith(('about/', 'experiences/')) and path.name not in ['hero-section.php', 'section.php']:
                return False
            if relative == 'logo.php' or relative.startswith('company-info/'):
                return False
        return not any(' copy' in p.lower() for p in path.parts)
    return name in ['index.php', '404.php', '.header_services.menu_ext.php', 'services/.left.menu_ext.php',
                    'apple-touch-icon.png', 'favicon-96x96.png', 'favicon.ico', 'favicon.svg', 'site.webmanifest',
                    'web-app-manifest-192x192.png', 'web-app-manifest-512x512.png',
                    'googleec11afadb3f0f130.html', 'yandex_80117ee37bc7e26e.html'] or name in [
                        folder + '/index.php' for folder in ['about', 'cases', 'contacts', 'events', 'experiences',
                        'news', 'partnership', 'prices', 'privacy-policy', 'publications', 'services', 'sitemap']]


def allowed_seed(name):
    path = PurePosixPath(name)
    return (name.startswith('include/') or path.name.endswith('.menu.php')
            or path.name == '.section.php' or name in ['urlrewrite.php', 'robots.txt'])


@contextmanager
def locked(state):
    state.mkdir(parents=True, mode=0o700, exist_ok=True)
    with (state / 'lock').open('a') as handle:
        try:
            fcntl.flock(handle, fcntl.LOCK_EX | fcntl.LOCK_NB)
        except BlockingIOError:
            raise RuntimeError('Another deployment is running')
        yield


@contextmanager
def backup_progress(interval=20):
    stopped = threading.Event()
    def report():
        while not stopped.wait(interval):
            print('Backup is still running; application code has not been changed.', flush=True)
    thread = threading.Thread(target=report, daemon=True)
    thread.start()
    try:
        yield
    finally:
        stopped.set()
        thread.join()


def unpack(archive, destination):
    # Reject symlinks, hard links, devices, duplicate paths and path traversal.
    with tarfile.open(archive, 'r:gz') as source:
        seen = set()
        for member in source.getmembers():
            path = safe_path(destination, member.name)
            if not member.isfile() or member.name in seen:
                raise RuntimeError('Release archive must contain unique regular files')
            seen.add(member.name)
            path.parent.mkdir(parents=True, exist_ok=True)
            with source.extractfile(member) as incoming, path.open('wb') as output:
                shutil.copyfileobj(incoming, output)
    metadata = load_json(destination / 'release.json')
    if metadata.get('version') != 1 or not re.fullmatch('[0-9a-f]{40}', metadata.get('revision', '')):
        raise RuntimeError('Unsupported release metadata')
    expected = {'release.json', *['ops/' + n for n in ['remote.py', 'backup.py', 'db-settings.php', 'clear-cache.php']]}
    for name, checksum in metadata['code'].items():
        if not allowed_code(name):
            raise RuntimeError('Protected path in code manifest: ' + name)
        path = safe_path(destination, 'code/' + name)
        if sha(path) != checksum:
            raise RuntimeError('Checksum mismatch: ' + name)
        expected.add('code/' + name)
    for name, seed in metadata['seeds'].items():
        if not allowed_seed(name) or sha(safe_path(destination, 'defaults/' + name)) != seed['sha256']:
            raise RuntimeError('Invalid seed: ' + name)
        if seed.get('legacy'):
            safe_path(destination, seed['legacy'])
        expected.add('defaults/' + name)
    if seen != expected:
        raise RuntimeError('Archive contents differ from manifest')
    return metadata


def check_drift(root, metadata):
    for name, checksum in metadata.get('code', {}).items():
        path = safe_path(root, name)
        if not path.is_file() or sha(path) != checksum:
            raise RuntimeError('Managed code changed on server; save and reconcile before deployment: ' + name)


def changes(root, previous, incoming):
    check_drift(root, previous)
    writes = []
    for name, checksum in incoming['code'].items():
        path = safe_path(root, name)
        if not path.exists() or sha(path) != checksum:
            writes.append(name)
    # Retain old hashed assets so previously cached HTML continues to work.
    deletes = [n for n in previous.get('code', {}) if n not in incoming['code'] and '/_dist/' not in n]
    seeds = []
    for name in incoming['seeds']:
        if not safe_path(root, name).exists():
            seeds.append(name)
    return sorted(writes), sorted(deletes), sorted(seeds)


def snapshot(root, destination, names, previous):
    destination.mkdir(parents=True, mode=0o700)
    absent = []
    expected = {}
    with tarfile.open(destination / 'code.tar.gz', 'w:gz') as archive:
        for name in names:
            path = safe_path(root, name)
            if path.exists():
                expected[name] = sha(path)
                archive.add(path, arcname=name, recursive=False)
            else:
                expected[name] = None
                absent.append(name)
    write_json(destination / 'before.json', {'previous': previous, 'absent': absent, 'paths': names, 'expected': expected})


def check_snapshot(root, backup):
    for name, checksum in load_json(backup / 'before.json')['expected'].items():
        path = safe_path(root, name)
        if (sha(path) if path.exists() else None) != checksum:
            raise RuntimeError('Server code changed during backup: ' + name)


def restore_code(root, state, backup):
    before = load_json(backup / 'before.json')
    for name in before['absent']:
        path = safe_path(root, name)
        unlink_if_exists(path)
    with tarfile.open(backup / 'code.tar.gz', 'r:gz') as archive:
        for member in archive:
            if not member.isfile() or member.name not in before['paths']:
                raise RuntimeError('Invalid code snapshot')
            path = safe_path(root, member.name)
            atomic_bytes(path, archive.extractfile(member).read(), member.mode)
    write_json(state / 'current.json', before['previous'])


def seed_content(root, unpacked, incoming, config):
    group = grp.getgrnam(config['content_group']).gr_gid if config.get('content_group') else None
    for name, spec in incoming['seeds'].items():
        target = safe_path(root, name)
        if target.exists():
            continue
        source = unpacked / 'defaults' / name
        if spec.get('legacy'):
            existing = safe_path(root, spec['legacy'])
            if existing.is_file():
                source = existing
        public_dirs(target.parent, 0o2775 if group is not None else 0o755, group)
        # Publish a complete file exclusively; preserve a concurrent editor's file.
        handle, temporary = tempfile.mkstemp(prefix='.deploy-content-', dir=target.parent)
        try:
            with os.fdopen(handle, 'wb') as output:
                output.write(source.read_bytes())
                output.flush()
                os.fsync(output.fileno())
            if group is not None:
                os.chown(temporary, -1, group)
            os.chmod(temporary, 0o664 if group is not None else 0o644)
            os.link(temporary, target)
        except FileExistsError:
            pass
        finally:
            os.unlink(temporary)


def clear_cache(root, ops, config):
    if not config.get('clear_cache', True):
        return
    host = urllib.parse.urlparse(config['health_checks'][0]['url']).hostname
    subprocess.run([config.get('php', 'php'), '-d', 'short_open_tag=1',
                    str(ops / 'clear-cache.php'), str(root), host], check=True)


def health(config, revision):
    checks = config.get('health_checks', [])
    if not checks:
        raise RuntimeError('Configure at least one HTTP health check')
    for check in checks:
        url = check['url']
        if urllib.parse.urlparse(url).scheme not in ['http', 'https']:
            raise RuntimeError('Health checks require http(s) URLs')
        separator = '&' if '?' in url else '?'
        request = urllib.request.Request(url + separator + '_deploy=' + revision,
                                         headers={'Cache-Control': 'no-cache', 'User-Agent': 'Usticom-Deploy/1'})
        last_error = None
        for attempt in range(3):
            try:
                with urllib.request.urlopen(request, timeout=20) as response:
                    body = response.read(5_000_000).decode('utf-8', errors='replace')
                    if response.status != 200 or urllib.parse.urlparse(response.url).netloc != urllib.parse.urlparse(url).netloc:
                        raise RuntimeError('Unexpected HTTP status or redirect host')
                    if re.search(r'(Fatal error:|Parse error:|Uncaught (?:Error|Exception))', body):
                        raise RuntimeError('PHP error in HTTP response')
                    if check.get('contains') and check['contains'] not in body:
                        raise RuntimeError('Expected page content is missing')
                last_error = None
                break
            except Exception as error:
                last_error = error
                if attempt < 2:
                    time.sleep(2)
        if last_error:
            raise RuntimeError('Health check failed: ' + url) from last_error


def apply(root, state, archive, plan=False):
    config = load_json(state / 'config.json')
    if not config or Path(config['root']).resolve() != root:
        raise RuntimeError('Initialize server config with the correct document root first')
    if (state / 'pending.json').exists():
        raise RuntimeError('Interrupted deployment detected. Run recover before deploying again.')
    with tempfile.TemporaryDirectory(dir=state) as temporary:
        unpacked = Path(temporary)
        incoming = unpack(archive, unpacked)
        previous = load_json(state / 'current.json', {'code': {}})
        writes, deletes, seeds = changes(root, previous, incoming)
        print(json.dumps({'revision': incoming['revision'], 'write': writes, 'delete': deletes,
                          'create_content_if_missing': seeds}, ensure_ascii=False, indent=2), flush=True)
        if plan:
            return
        if not (state / 'current.json').exists() and not config.get('allow_initial_deploy', False):
            raise RuntimeError('First deployment requires an inspected plan and allow_initial_deploy=true')
        # Check PHP syntax on the target PHP version before backup or writes.
        for name in incoming['code']:
            if name.endswith('.php'):
                subprocess.run([config.get('php', 'php'), '-d', 'short_open_tag=1', '-l',
                                str(unpacked / 'code' / name)], check=True, stdout=subprocess.DEVNULL)
        for required in ['include/phone.php', 'include/mail.php', 'include/address.php', 'urlrewrite.php']:
            if not safe_path(root, required).is_file():
                raise RuntimeError('Restore or fill required content before deploying: ' + required)
        health(config, 'preflight')
        stamp = datetime.now(timezone.utc).strftime('%Y%m%dT%H%M%S%fZ') + '-' + incoming['revision'][:12]
        backup = state / 'backups' / stamp
        snapshot(root, backup, sorted(set(writes + deletes)), previous)
        with backup_progress():
            subprocess.run([config.get('python', 'python3'), str(unpacked / 'ops/backup.py'),
                            '--root', str(root), '--destination', str(backup / 'data'),
                            '--php', config.get('php', 'php')], check=True)
        # Refuse server edits that happened while the backup was running.
        check_drift(root, previous)
        check_snapshot(root, backup)
        shutil.copytree(unpacked / 'ops', backup / 'ops')
        write_json(state / 'pending.json', {'backup': stamp, 'revision': incoming['revision']})
        try:
            seed_content(root, unpacked, incoming, config)
            # Assets go first and the manifest last to reduce the transition window.
            writes.sort(key=lambda name: (name.endswith('/manifest.json'), '/_dist/' not in name, name))
            for name in writes:
                path = safe_path(root, name)
                atomic_bytes(path, (unpacked / 'code' / name).read_bytes(), 0o644)
            for name in deletes:
                safe_path(root, name).unlink()
            clear_cache(root, unpacked / 'ops', config)
            health(config, incoming['revision'])
            atomic_bytes(state / 'remote.py', (unpacked / 'ops/remote.py').read_bytes(), 0o600)
            write_json(state / 'current.json', incoming)
            write_json(state / 'last-success.json', {'backup': stamp, 'revision': incoming['revision']})
            (state / 'pending.json').unlink()
        except BaseException:
            restore_code(root, state, backup)
            try:
                clear_cache(root, unpacked / 'ops', config)
                health(config, 'rollback')
            except Exception:
                print('Code restored, but the rollback health check failed. Inspect server and run recover.', flush=True)
                raise
            (state / 'pending.json').unlink()
            print('Deployment failed. Previous code restored; DB and editor content were not restored.', flush=True)
            raise
        print('Deployment completed: ' + incoming['revision'], flush=True)


def recover(root, state, manual=False):
    journal = load_json(state / ('last-success.json' if manual else 'pending.json'))
    if not journal:
        raise RuntimeError('No deployment to recover or roll back')
    backup = safe_path(state, 'backups/' + journal['backup'] + '/before.json').parent
    if manual:
        check_drift(root, load_json(state / 'current.json', {'code': {}}))
        # Save current code too so an operator can undo a manual rollback.
        rescue = state / 'backups' / ('before-rollback-' + datetime.now(timezone.utc).strftime('%Y%m%dT%H%M%S%fZ'))
        before = load_json(backup / 'before.json')
        snapshot(root, rescue, before['paths'], load_json(state / 'current.json'))
        write_json(state / 'pending.json', {'backup': journal['backup'], 'revision': 'rollback'})
    restore_code(root, state, backup)
    config = load_json(state / 'config.json')
    clear_cache(root, backup / 'ops', config)
    health(config, 'rollback')
    unlink_if_exists(state / 'pending.json')
    if manual:
        (state / 'last-success.json').unlink()
    print('Previous code restored. Database and editor content preserved.')


def main():
    os.umask(0o077)
    parser = argparse.ArgumentParser(description=__doc__)
    parser.add_argument('command', choices=['plan', 'apply', 'recover', 'rollback'])
    parser.add_argument('--root', required=True)
    parser.add_argument('--state', required=True)
    parser.add_argument('--archive')
    args = parser.parse_args()
    root, state = Path(args.root).resolve(), Path(args.state).resolve()
    if root == state or root in state.parents or state in root.parents:
        raise RuntimeError('State and document root must be separate directory trees')
    if not (root / 'bitrix/modules/main/include/prolog_before.php').is_file():
        raise RuntimeError('Restore Bitrix before deployment')
    with locked(state):
        if args.command in ['plan', 'apply']:
            if not args.archive:
                parser.error('--archive is required')
            apply(root, state, Path(args.archive).resolve(), plan=args.command == 'plan')
        else:
            recover(root, state, manual=args.command == 'rollback')


if __name__ == '__main__':
    main()
