#!/usr/bin/env python3
"""Transport the release over SSH; credentials are supplied through CI environment."""
import argparse
import os
from pathlib import Path
import re
import shlex
import subprocess


def ship(archive, command):
    host, user = os.environ['DEPLOY_HOST'], os.environ['DEPLOY_USER']
    port = os.environ.get('DEPLOY_PORT', '22')
    root, state = os.environ['DEPLOY_ROOT'], os.environ['DEPLOY_STATE']
    if not re.fullmatch(r'[A-Za-z0-9_.:-]+', host) or not re.fullmatch(r'[A-Za-z0-9_.-]+', user):
        raise RuntimeError('Invalid SSH host or username')
    if not port.isdigit() or not 0 < int(port) < 65536:
        raise RuntimeError('Invalid SSH port')
    for path in [root, state]:
        if not re.fullmatch(r'/[A-Za-z0-9_./-]+', path) or '..' in Path(path).parts:
            raise RuntimeError('Use absolute server paths without spaces or shell characters')
    ssh = ['ssh', '-p', port, '-o', 'BatchMode=yes', '-o', 'StrictHostKeyChecking=yes',
           '-o', 'ConnectTimeout=15', '-o', 'ServerAliveInterval=15', '-o', 'ServerAliveCountMax=4',
           user + '@' + host]
    def run(*arguments):
        subprocess.run(ssh + [shlex.join(arguments)], check=True)
    if command in ['apply', 'plan']:
        run('test', '-f', state + '/config.json')
        revision = os.environ.get('GITHUB_SHA', 'local')
        run_id = os.environ.get('GITHUB_RUN_ID', 'manual') + '-' + os.environ.get('GITHUB_RUN_ATTEMPT', '1')
        if not re.fullmatch(r'[a-zA-Z0-9-]+', revision + '-' + run_id):
            raise RuntimeError('Invalid release identifier')
        destination = state + '/incoming/' + revision + '-' + run_id
        run('mkdir', '-m', '700', '-p', destination)
        try:
            # Stream files with ssh instead of shell-dependent legacy scp path handling.
            for source, target in [(Path(archive), destination + '/release.tar.gz'),
                                   (Path(__file__).with_name('remote.py'), destination + '/remote.py')]:
                with source.open('rb') as incoming:
                    subprocess.run(ssh + ['umask 077 && cat > ' + shlex.quote(target)], stdin=incoming, check=True)
            run('python3', destination + '/remote.py', command, '--root', root, '--state', state,
                '--archive', destination + '/release.tar.gz')
        finally:
            # Cleanup only this run's two transport files; snapshots remain on the server.
            for name in ['remote.py', 'release.tar.gz']:
                subprocess.run(ssh + [shlex.join(['rm', '-f', destination + '/' + name])], check=False)
            subprocess.run(ssh + [shlex.join(['rmdir', destination])], check=False)
    else:
        raise RuntimeError('Use the server runner for recover and rollback')


if __name__ == '__main__':
    parser = argparse.ArgumentParser(description=__doc__)
    parser.add_argument('command', choices=['apply', 'plan'])
    parser.add_argument('--archive', required=True)
    args = parser.parse_args()
    ship(args.archive, args.command)
