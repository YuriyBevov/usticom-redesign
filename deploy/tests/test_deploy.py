import contextlib
import hashlib
import gzip
import io
import json
import os
import shutil
from pathlib import Path
import stat
import subprocess
import sys
import tarfile
import tempfile
import unittest
from unittest.mock import patch

DEPLOY = Path(__file__).resolve().parents[1]
sys.path.insert(0, str(DEPLOY))
import remote
import release
import backup


def checksum(data):
    return hashlib.sha256(data.encode()).hexdigest()


class DeploymentTests(unittest.TestCase):
    def setUp(self):
        self.work = tempfile.TemporaryDirectory()
        self.base = Path(self.work.name).resolve()
        self.root, self.state = self.base / 'www', self.base / 'state'
        self.root.mkdir()
        self.state.mkdir()
        for name, value in {'index.php': 'old code', 'include/phone.php': 'editor phone',
                            'include/mail.php': 'editor email', 'include/address.php': 'editor address',
                            'urlrewrite.php': 'editor routes', 'upload/photo.txt': 'editor image',
                            'bitrix/.settings.php': 'server secrets', '.top.menu.php': 'editor menu'}.items():
            self.put(name, value)
        self.config = {'root': str(self.root), 'clear_cache': False, 'allow_initial_deploy': True,
                       'health_checks': [{'url': 'https://example.invalid/'}]}
        remote.write_json(self.state / 'config.json', self.config)
        self.health_calls = 0
        self.backup_failed = False
        self.called_backup = False
        self.run_patch = patch.object(remote.subprocess, 'run', side_effect=self.subprocess)
        self.health_patch = patch.object(remote, 'health', side_effect=self.health)
        self.run_patch.start()
        self.health_patch.start()

    def tearDown(self):
        self.health_patch.stop()
        self.run_patch.stop()
        self.work.cleanup()

    def put(self, name, value):
        path = self.root / name
        path.parent.mkdir(parents=True, exist_ok=True)
        path.write_text(value)
        return path

    def subprocess(self, command, **kwargs):
        if any(str(part).endswith('/backup.py') for part in command):
            self.called_backup = True
            self.assertEqual((self.root / 'index.php').read_text(), self.before_backup)
            if self.backup_failed:
                raise RuntimeError('DB backup failed')
            target = Path(command[command.index('--destination') + 1])
            target.mkdir()
            (target / 'complete.json').write_text('{}')
        return None

    def health(self, config, revision):
        self.health_calls += 1

    def artifact(self, code=None, seeds=None, legacy=None, revision='a' * 40):
        code = code or {'index.php': 'new code'}
        seeds, legacy = seeds or {}, legacy or {}
        metadata = {'version': 1, 'revision': revision, 'code': {n: checksum(v) for n, v in code.items()},
                    'seeds': {n: {'sha256': checksum(v), 'legacy': legacy.get(n)} for n, v in seeds.items()}}
        archive = self.base / (revision + '.tar.gz')
        files = {'release.json': json.dumps(metadata)}
        files.update({'code/' + n: v for n, v in code.items()})
        files.update({'defaults/' + n: v for n, v in seeds.items()})
        files.update({'ops/' + n: '# fixture' for n in ['remote.py', 'backup.py', 'db-settings.php', 'clear-cache.php']})
        with tarfile.open(archive, 'w:gz') as output:
            for name, value in files.items():
                data = value.encode()
                info = tarfile.TarInfo(name)
                info.size = len(data)
                output.addfile(info, io.BytesIO(data))
        return archive

    def apply(self, archive, plan=False):
        self.before_backup = (self.root / 'index.php').read_text()
        with contextlib.redirect_stdout(io.StringIO()):
            remote.apply(self.root, self.state, archive, plan=plan)

    def test_content_and_existing_menu_preserved_and_legacy_migrated_once(self):
        legacy = 'local/templates/littleweb/include/about/title.php'
        self.put(legacy, 'updated by editor')
        archive = self.artifact(seeds={'include/about/title.php': 'developer default',
                                       'include/empty.php': 'default', '.top.menu.php': 'default menu'},
                                legacy={'include/about/title.php': legacy})
        self.put('include/empty.php', '')
        self.apply(archive)
        self.assertTrue(self.called_backup)
        self.assertEqual((self.root / 'index.php').read_text(), 'new code')
        self.assertEqual((self.root / 'include/about/title.php').read_text(), 'updated by editor')
        self.assertEqual((self.root / 'include/empty.php').read_text(), '')
        self.assertEqual((self.root / '.top.menu.php').read_text(), 'editor menu')
        self.assertEqual((self.root / 'upload/photo.txt').read_text(), 'editor image')
        self.assertEqual((self.root / 'bitrix/.settings.php').read_text(), 'server secrets')
        self.put('include/about/title.php', 'new editor version')
        self.apply(archive)
        self.assertEqual((self.root / 'include/about/title.php').read_text(), 'new editor version')
        self.assertFalse((self.state / 'pending.json').exists())

    def test_plan_has_no_mutations_or_backup(self):
        self.apply(self.artifact(seeds={'include/title.php': 'default'}), plan=True)
        self.assertFalse(self.called_backup)
        self.assertEqual((self.root / 'index.php').read_text(), 'old code')
        self.assertFalse((self.root / 'include/title.php').exists())
        self.assertFalse((self.state / 'current.json').exists())

    def test_failed_backup_prevents_all_writes(self):
        self.backup_failed = True
        with self.assertRaisesRegex(RuntimeError, 'DB backup'):
            self.apply(self.artifact(seeds={'include/title.php': 'default'}))
        self.assertEqual((self.root / 'index.php').read_text(), 'old code')
        self.assertFalse((self.root / 'include/title.php').exists())
        self.assertFalse((self.state / 'pending.json').exists())

    def test_content_migrations_run_after_backup_and_code_installation(self):
        original = self.subprocess
        calls = []
        def run(command, **kwargs):
            if str(self.root / 'local/tools/migrate.php') in command:
                self.assertTrue(self.called_backup)
                self.assertEqual((self.root / 'index.php').read_text(), 'new code')
                self.assertTrue((self.root / 'en/index.php').exists())
                self.assertEqual(self.health_calls, 1)  # The pre-deployment check has already run.
                self.assertIn('--apply', command)
                calls.append(command)
            return original(command, **kwargs)
        remote.subprocess.run.side_effect = run
        self.apply(self.artifact(code={'index.php': 'new code', 'en/index.php': '<?php',
                                      'local/tools/migrate.php': '<?php'}))
        self.assertEqual(len(calls), 1)

    def test_failed_content_migration_restores_code_and_preserves_editor_content(self):
        original = self.subprocess
        def run(command, **kwargs):
            if str(self.root / 'local/tools/migrate.php') in command:
                self.assertTrue(self.called_backup)
                self.put('en/include/address.php', 'manager edited address')
                raise RuntimeError('Content migration failed')
            return original(command, **kwargs)
        remote.subprocess.run.side_effect = run
        with self.assertRaisesRegex(RuntimeError, 'Content migration failed'):
            self.apply(self.artifact(code={'index.php': 'new code', 'local/tools/migrate.php': '<?php'},
                                     seeds={'en/include/address.php': 'initial address'}))
        self.assertEqual((self.root / 'index.php').read_text(), 'old code')
        self.assertEqual((self.root / 'en/include/address.php').read_text(), 'manager edited address')
        self.assertFalse((self.state / 'pending.json').exists())

    def test_code_edited_during_first_backup_is_not_overwritten(self):
        original = self.subprocess
        def edit(command, **kwargs):
            result = original(command, **kwargs)
            if any(str(part).endswith('/backup.py') for part in command):
                self.put('index.php', 'hotfix during backup')
            return result
        remote.subprocess.run.side_effect = edit
        with self.assertRaisesRegex(RuntimeError, 'changed during backup'):
            self.apply(self.artifact())
        self.assertEqual((self.root / 'index.php').read_text(), 'hotfix during backup')
        self.assertFalse((self.state / 'pending.json').exists())

    def test_failed_health_rolls_back_code_but_preserves_new_editor_content(self):
        def health(config, revision):
            if revision == 'a' * 40:
                self.put('include/phone.php', 'edited during deploy')
                raise RuntimeError('unhealthy')
        remote.health.side_effect = health
        archive = self.artifact(code={'index.php': 'bad code', '404.php': 'new file'},
                                seeds={'include/title.php': 'default'})
        with self.assertRaisesRegex(RuntimeError, 'unhealthy'):
            self.apply(archive)
        self.assertEqual((self.root / 'index.php').read_text(), 'old code')
        self.assertFalse((self.root / '404.php').exists())
        self.assertEqual((self.root / 'include/phone.php').read_text(), 'edited during deploy')
        self.assertEqual((self.root / 'include/title.php').read_text(), 'default')
        self.assertFalse((self.state / 'pending.json').exists())

    def test_server_code_drift_stops_next_deploy(self):
        self.apply(self.artifact())
        self.put('index.php', 'server hotfix')
        self.called_backup = False
        with self.assertRaisesRegex(RuntimeError, 'changed on server'):
            self.apply(self.artifact(revision='b' * 40))
        self.assertEqual((self.root / 'index.php').read_text(), 'server hotfix')
        self.assertFalse(self.called_backup)

    def test_removed_managed_code_deleted_and_old_assets_and_unmanaged_files_retained(self):
        asset = 'local/templates/littleweb/_dist/old.css'
        self.put('unmanaged.php', 'preserve')
        self.apply(self.artifact(code={'index.php': 'v1', '404.php': 'old', asset: 'css'}))
        self.apply(self.artifact(code={'index.php': 'v2'}, revision='b' * 40))
        self.assertFalse((self.root / '404.php').exists())
        self.assertEqual((self.root / asset).read_text(), 'css')
        self.assertEqual((self.root / 'unmanaged.php').read_text(), 'preserve')

    def test_manual_rollback_preserves_content(self):
        self.apply(self.artifact())
        self.put('include/phone.php', 'current phone')
        with contextlib.redirect_stdout(io.StringIO()):
            remote.recover(self.root, self.state, manual=True)
        self.assertEqual((self.root / 'index.php').read_text(), 'old code')
        self.assertEqual((self.root / 'include/phone.php').read_text(), 'current phone')
        self.assertTrue(list((self.state / 'backups').glob('before-rollback-*')))

    def test_interrupted_deploy_requires_recovery(self):
        remote.write_json(self.state / 'pending.json', {'backup': 'incomplete'})
        with self.assertRaisesRegex(RuntimeError, 'Interrupted'):
            self.apply(self.artifact())
        self.assertEqual((self.root / 'index.php').read_text(), 'old code')

    def test_recover_interrupted_code_write(self):
        previous = {'code': {}}
        snapshot = self.state / 'backups' / 'interrupted'
        remote.snapshot(self.root, snapshot, ['index.php', '404.php'], previous)
        self.put('index.php', 'partial update')
        self.put('404.php', 'partial new file')
        remote.write_json(self.state / 'pending.json', {'backup': 'interrupted'})
        with contextlib.redirect_stdout(io.StringIO()):
            remote.recover(self.root, self.state)
        self.assertEqual((self.root / 'index.php').read_text(), 'old code')
        self.assertFalse((self.root / '404.php').exists())
        self.assertFalse((self.state / 'pending.json').exists())

    def test_protected_files_and_legacy_content_cannot_enter_code(self):
        for name in ['bitrix/.settings.php', 'upload/image.php', 'include/text.php', '.top.menu.php',
                     'urlrewrite.php', 'local/templates/littleweb/include/about/title.php',
                     'local/templates/littleweb/include/logo.php']:
            with self.subTest(name=name), self.assertRaisesRegex(RuntimeError, 'Protected path'):
                self.apply(self.artifact(code={name: 'forbidden'}))

    def test_symlinks_are_not_followed_for_managed_code_or_content(self):
        outside = self.base / 'outside'
        outside.write_text('outside')
        (self.root / 'index.php').unlink()
        (self.root / 'index.php').symlink_to(outside)
        with self.assertRaisesRegex(RuntimeError, 'Symlink'):
            self.apply(self.artifact())
        self.assertEqual(outside.read_text(), 'outside')

    def test_archive_traversal_and_links_rejected(self):
        for name, kind in [('../escape', tarfile.REGTYPE), ('code/link', tarfile.SYMTYPE),
                           ('code/hard', tarfile.LNKTYPE)]:
            archive = self.base / 'unsafe.tar.gz'
            with tarfile.open(archive, 'w:gz') as output:
                info = tarfile.TarInfo(name)
                info.type = kind
                output.addfile(info)
            with self.subTest(name=name), self.assertRaises(RuntimeError):
                self.apply(archive)
        self.assertFalse((self.base / 'escape').exists())

    def test_new_directories_readable_under_private_umask(self):
        previous = os.umask(0o077)
        try:
            remote.atomic_bytes(self.root / 'new/nested/code.php', b'code')
            self.apply(self.artifact(seeds={'include/about/title.php': 'title'}))
        finally:
            os.umask(previous)
        self.assertEqual(stat.S_IMODE((self.root / 'new/nested').stat().st_mode), 0o755)
        self.assertEqual(stat.S_IMODE((self.root / 'include/about').stat().st_mode), 0o755)
        self.assertEqual(stat.S_IMODE((self.root / 'include/about/title.php').stat().st_mode), 0o644)

    def test_explicit_private_php_group_gets_content_write_permissions(self):
        import grp
        self.config['content_group'] = grp.getgrgid(os.getgid()).gr_name
        remote.write_json(self.state / 'config.json', self.config)
        self.apply(self.artifact(seeds={'include/about/title.php': 'title'}))
        self.assertEqual(stat.S_IMODE((self.root / 'include/about').stat().st_mode), 0o2775)
        self.assertEqual(stat.S_IMODE((self.root / 'include/about/title.php').stat().st_mode), 0o664)

    def test_first_deployment_requires_explicit_initialization(self):
        self.config['allow_initial_deploy'] = False
        remote.write_json(self.state / 'config.json', self.config)
        with self.assertRaisesRegex(RuntimeError, 'First deployment'):
            self.apply(self.artifact())
        self.assertFalse(self.called_backup)


class PackagingPolicyTests(unittest.TestCase):
    def test_only_application_code_is_shipped(self):
        policy = json.loads((DEPLOY / 'policy.json').read_text())
        for name in ['__local/templates/Usticom/header.php', '_about/index.php', 'include/worktime.php',
                     'services/index copy.php', 'local/templates/littleweb/include copy/logo.php',
                     '.top.menu.php', '.section.php', 'urlrewrite.php', 'robots.txt', 'sitemap.xml',
                     'local/tools/bind-service-price-sections.php', '.env', 'tests/prices-catalog.php',
                     'local/templates/littleweb/include/about/title.php', 'en/.header_top.menu.php', 'en/include/address.php']:
            with self.subTest(name=name):
                self.assertFalse(release.is_code(name, policy))
        for name in ['index.php', 'services/index.php', '.header_services.menu_ext.php',
                     'local/php_interface/init.php', 'local/ajax/popup_form.php',
                     'local/templates/littleweb/include/about/hero-section.php',
                     'local/templates/littleweb/_src/images/inner-hero.png', 'en/index.php', 'en/services/index.php',
                     'en/contacts/index.php', 'en/.header_services.menu_ext.php', 'en/local/ajax/popup_form.php',
                     'local/tools/migrate.php', 'local/migrations/20261029_01_services_en.php',
                     'local/migrations/content/en-hr-outsourcing.json']:
            with self.subTest(name=name):
                self.assertTrue(release.is_code(name, policy))


class BackupTests(unittest.TestCase):
    def test_mysql_option_values_escape_quotes_and_newlines(self):
        self.assertEqual(backup.mysql_quote('a"b\nc\\d'), '"a\\"b\\nc\\\\d"')

    @unittest.skipUnless(shutil.which('php'), 'PHP CLI is tested in the main CI job')
    def test_backup_contains_database_uploads_and_settings_but_no_cache_or_credentials_tempfile(self):
        with tempfile.TemporaryDirectory() as temporary:
            work = Path(temporary).resolve()
            root, target = work / 'www', work / 'backups'
            (root / 'bitrix/cache').mkdir(parents=True)
            (root / 'include').mkdir()
            (root / '.top.menu.php').write_text('menu')
            (root / 'include/phone.php').write_text('phone')
            (root / 'bitrix/cache/generated').write_text('cache')
            (root / 'bitrix/.settings.php').write_text("<?php return ['connections'=>['value'=>['default'=>"
                "['host'=>'localhost','database'=>'test','login'=>'test','password'=>'test']]]];")
            uploads = work / 'shared-uploads'
            uploads.mkdir()
            (uploads / 'photo.txt').write_text('photo')
            (root / 'upload').symlink_to(uploads, target_is_directory=True)
            dump = work / 'fake-dump'
            dump.write_text("#!/bin/sh\nprintf '%s\\n' 'CREATE TABLE fixture (id int);'\n")
            dump.chmod(0o700)
            with patch.object(backup.shutil, 'which', return_value=str(dump)), contextlib.redirect_stdout(io.StringIO()):
                backup.backup(root, target)
            self.assertIn('CREATE TABLE fixture', gzip.decompress((target / 'database.sql.gz').read_bytes()).decode())
            with tarfile.open(target / 'content.tar.gz') as archive:
                self.assertIn('upload/photo.txt', archive.getnames())
                self.assertIn('bitrix/.settings.php', archive.getnames())
                self.assertIn('.top.menu.php', archive.getnames())
                self.assertNotIn('bitrix/cache/generated', archive.getnames())
            self.assertEqual(sorted(p.name for p in target.iterdir()), ['complete.json', 'content.tar.gz', 'database.sql.gz'])
            self.assertEqual(stat.S_IMODE(target.stat().st_mode), 0o700)


class HealthTests(unittest.TestCase):
    def test_backup_progress_reports_and_stops_on_error(self):
        import time
        output=io.StringIO()
        with contextlib.redirect_stdout(output), self.assertRaisesRegex(RuntimeError, 'fixture'):
            with remote.backup_progress(interval=0.01):
                time.sleep(0.04)
                raise RuntimeError('fixture')
        after=output.getvalue()
        self.assertIn('application code has not been changed',after)
        time.sleep(0.03)
        self.assertEqual(output.getvalue(),after)

    def test_php_error_in_http_200_is_a_failed_health_check(self):
        import http.server
        import threading
        class Handler(http.server.BaseHTTPRequestHandler):
            def do_GET(self):
                self.send_response(200)
                self.end_headers()
                self.wfile.write(b'Fatal error: fixture')
            def log_message(self, *args):
                pass
        server = http.server.HTTPServer(('127.0.0.1', 0), Handler)
        thread = threading.Thread(target=server.serve_forever, daemon=True)
        thread.start()
        try:
            with patch.object(remote.time, 'sleep'), self.assertRaisesRegex(RuntimeError, 'Health check failed'):
                remote.health({'health_checks': [{'url': f'http://127.0.0.1:{server.server_port}/'}]}, 'test')
        finally:
            server.shutdown()
            server.server_close()
            thread.join()


if __name__ == '__main__':
    unittest.main()
