#!/usr/bin/env python3
"""Create deployment state after restoring the site; does not deploy application code."""
import argparse
import grp
import os
from pathlib import Path
import shutil
import subprocess
import urllib.parse

from remote import locked, write_json


def initialize(root, state, url, group, php):
    root, state = Path(root).resolve(), Path(state).resolve()
    if root == state or root in state.parents or state in root.parents:
        raise RuntimeError('State must be outside the document root and its parents')
    if not (root / 'bitrix/modules/main/include/prolog_before.php').is_file():
        raise RuntimeError('Finish restoring the site first')
    if not url.startswith('https://') or not urllib.parse.urlparse(url).hostname:
        raise RuntimeError('Provide the real HTTPS origin')
    if not shutil.which(php) or not (shutil.which('mariadb-dump') or shutil.which('mysqldump')):
        raise RuntimeError('PHP CLI and mariadb-dump/mysqldump are required')
    if group:
        grp.getgrnam(group)
    subprocess.run([php, '-r', 'exit(PHP_VERSION_ID >= 80200 ? 0 : 1);'], check=True)
    with locked(state):
        if (state / 'config.json').exists():
            raise RuntimeError('Configuration already exists; inspect it instead of overwriting')
        origin = url.rstrip('/')
        config = {'root': str(root), 'content_group': group, 'php': php, 'python': 'python3',
                  'clear_cache': True, 'allow_initial_deploy': False,
                  'health_checks': [{'url': origin + '/', 'contains': 'Юстиком'},
                                    {'url': origin + '/contacts/', 'contains': 'contacts__'},
                                    {'url': origin + '/prices/', 'contains': '/local/templates/littleweb/_dist/'}]}
        write_json(state / 'config.json', config)
        shutil.copyfile(Path(__file__).with_name('remote.py'), state / 'remote.py')
        (state / 'remote.py').chmod(0o600)
    print('State initialized. Inspect a release plan before enabling the first deployment.')


if __name__ == '__main__':
    os.umask(0o077)
    parser = argparse.ArgumentParser(description=__doc__)
    parser.add_argument('--root', required=True)
    parser.add_argument('--state', required=True)
    parser.add_argument('--url', required=True)
    parser.add_argument('--web-group', help='Dedicated PHP group; omit when PHP runs as the hosting account owner')
    parser.add_argument('--php', default='php')
    args = parser.parse_args()
    initialize(args.root, args.state, args.url, args.web_group, args.php)
