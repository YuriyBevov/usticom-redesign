import assert from 'node:assert/strict';
import fs from 'node:fs';
import vm from 'node:vm';

const root = new URL('../', import.meta.url);
const dist = new URL('local/templates/littleweb/_dist/', root);
const manifest = JSON.parse(fs.readFileSync(new URL('manifest.json', dist), 'utf8'));
const readEntry = (entry) => fs.readFileSync(new URL(manifest[entry].file, dist), 'utf8');
const globalScript = readEntry('local/templates/littleweb/_src/js/index.js');
const formScript = readEntry('local/templates/littleweb/components/bitrix/form.result.new/form-inline/_src/js/index.js');
const context = vm.createContext({});

// Браузер подключает сборки обычными script: проверяем общий lexical scope.
// DOM здесь отсутствует; ошибки обращения к DOM допустимы, конфликты объявлений — нет.
for (const [index, source] of [globalScript, formScript, formScript, formScript].entries()) {
	try {
		vm.runInContext(source, context, { timeout: 1000 });
	} catch (error) {
		assert.notEqual(error.name, 'SyntaxError', `Конфликт объявлений при загрузке ${index}: ${error.message}`);
		assert.equal(error.name, 'ReferenceError', error.message);
	}
}
console.log('OK: глобальный скрипт и повторные загрузки формы не конфликтуют');
