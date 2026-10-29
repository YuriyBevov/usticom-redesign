#!/usr/bin/env python3
"""Create a private DB dump and an archive of mutable files before deployment."""
import argparse
import gzip
import json
import os
from pathlib import Path
import shutil
import subprocess
import tarfile
import tempfile


def mysql_quote(value):
    return '"' + str(value).replace('\\', '\\\\').replace('"', '\\"').replace('\n', '\\n').replace('\r', '\\r') + '"'


def backup(root, destination, php='php'):
    root, destination = Path(root).resolve(), Path(destination).resolve()
    if root == destination or root in destination.parents:
        raise RuntimeError('Backups must be outside the document root')
    destination.mkdir(parents=True, mode=0o700, exist_ok=False)
    settings = subprocess.run([php, str(Path(__file__).with_name('db-settings.php')), str(root)],
                              check=True, stdout=subprocess.PIPE, stderr=subprocess.PIPE)
    connection = json.loads(settings.stdout)
    executable = shutil.which('mariadb-dump') or shutil.which('mysqldump')
    if not executable:
        raise RuntimeError('Install mariadb-dump or mysqldump')
    host = connection.get('host', 'localhost')
    options = {'host': host, 'user': connection['login'], 'password': connection.get('password', '')}
    # Bitrix commonly stores host:port or host:/path/to/socket in the host field.
    if ':' in host and not host.startswith('['):
        hostname, suffix = host.split(':', 1)
        if suffix.isdigit():
            options.update(host=hostname, port=suffix)
        elif suffix.startswith('/'):
            options.update(host=hostname, socket=suffix)
    if connection.get('port'):
        options['port'] = connection['port']
    with tempfile.TemporaryDirectory(dir=destination) as temporary:
        credentials = Path(temporary) / 'mysql.cnf'
        credentials.write_text('[client]\n' + ''.join(f'{k}={mysql_quote(v)}\n' for k, v in options.items()))
        credentials.chmod(0o600)
        raw = Path(temporary) / 'database.sql'
        with raw.open('wb') as output:
            result = subprocess.run([executable, '--defaults-extra-file=' + str(credentials),
                                     '--single-transaction', '--quick', '--hex-blob', '--triggers', '--routines', '--events',
                                     '--', connection['database']], stdout=output, stderr=subprocess.PIPE)
        if result.returncode or not raw.stat().st_size:
            raise RuntimeError('Database dump failed; deployment stopped. Check DB backup privileges on server.')
        with raw.open('rb') as source, gzip.open(destination / 'database.sql.gz', 'wb') as output:
            shutil.copyfileobj(source, output)
    # Back up CMS files as well as uploads: sufficient to restore the original site with code.tar.gz.
    # Existing cache trees can be regenerated and are intentionally omitted.
    skipped = {'cache', 'managed_cache', 'stack_cache', 'html_pages', 'tmp', 'backup'}
    with tarfile.open(destination / 'content.tar.gz', 'w:gz', dereference=False) as archive:
        for top in ['include', 'upload', 'bitrix', 'local/templates/littleweb/include',
                    'local/.settings.php', 'local/.settings_extra.php', 'local/php_interface/dbconn.php',
                    '.htaccess', '.access.php', 'urlrewrite.php', 'robots.txt']:
            path = root / top
            if path.exists() or path.is_symlink():
                def select(member):
                    parts = Path(member.name).parts
                    if len(parts) > 1 and parts[0] == 'bitrix' and parts[1] in skipped:
                        return None
                    return member
                # Resolve the known top-level link (common for /upload) but preserve inner links.
                archive.add(path.resolve(), arcname=top, filter=select)
        for directory, subdirs, files in os.walk(root, followlinks=False):
            subdirs[:] = [n for n in subdirs if n not in ['bitrix', 'upload', 'include', '.git', 'node_modules', 'cache']]
            for name in files:
                if name.endswith('.menu.php') or name in ['.section.php', '.access.php'] or (name.startswith('sitemap') and name.endswith('.xml')):
                    path = Path(directory) / name
                    archive.add(path, arcname=path.relative_to(root).as_posix(), recursive=False)
    (destination / 'complete.json').write_text(json.dumps({'root': str(root), 'database': True, 'content': True}) + '\n')
    for path in destination.iterdir():
        path.chmod(0o600)
    print('Database and content backup completed')


if __name__ == '__main__':
    os.umask(0o077)
    parser = argparse.ArgumentParser(description=__doc__)
    parser.add_argument('--root', required=True)
    parser.add_argument('--destination', required=True)
    parser.add_argument('--php', default='php')
    args = parser.parse_args()
    backup(args.root, args.destination, args.php)
