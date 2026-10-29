<?php
// Автономная проверка шаблона без подключения к базе Битрикс.
if (PHP_SAPI !== "cli") {
	http_response_code(404);
	exit;
}

define("B_PROLOG_INCLUDED", true);
define("SITE_TEMPLATE_PATH", "/local/templates/littleweb");

function check(bool $condition, string $message): void
{
	if (!$condition) {
		throw new RuntimeException($message);
	}
}

function htmlspecialcharsbx($value): string
{
	return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
}

function getLinkedServicePriceSection(int $iblockId, int $sectionId): ?array
{
	return $GLOBALS['testLinkedServiceSections'][$sectionId] ?? null;
}

class PriceRows
{
	private array $rows;
	private int $index = 0;
	private string $url = "";
	public function __construct(array $rows) { $this->rows = array_values($rows); }
	public function SetUrlTemplates(string $url): void { $this->url = $url; }
	public function Fetch() { return $this->rows[$this->index++] ?? false; }
	public function GetNext()
	{
		$row = $this->Fetch();
		if ($row && $this->url !== "") {
			$row["DETAIL_PAGE_URL"] = str_replace(["#SECTION_CODE#", "#ELEMENT_CODE#"], ["subsection-" . $row["IBLOCK_SECTION_ID"], $row["CODE"]], $this->url);
		}
		return $row;
	}
}

class CIBlockElement
{
	public static array $items = [];
	public static array $groups = [];
	public static int $queries = 0;
	public static function GetList($sort, $filter, $group, $navigation, $select): PriceRows
	{
		self::$queries++;
		check($navigation === false, "Прайс не должен обрезаться постраничной навигацией");
		check($filter["IBLOCK_ID"] === 22, "Неверный инфоблок");
		foreach (["ACTIVE", "ACTIVE_DATE", "SECTION_GLOBAL_ACTIVE", "CHECK_PERMISSIONS"] as $key) {
			check($filter[$key] === "Y", "Не проверяется " . $key);
		}
		check($filter["INCLUDE_SUBSECTIONS"] === "N", "Элементы должны принадлежать указанным подразделам");
		$rows = [];
		foreach (self::$items as $id => $item) {
			$belongs = array_filter(self::$groups, static fn($group) => $group["IBLOCK_ELEMENT_ID"] === $id && in_array($group["ID"], $filter["SECTION_ID"], true));
			if ($belongs) {
				unset($item["PROPERTIES"]);
				$rows[] = $item;
			}
		}
		return new PriceRows($rows);
	}
	public static function GetPropertyValuesArray(&$items, $iblock, $filter, $properties): void
	{
		check($properties["CODE"] === ["PRICE", "NOTE"], "Должны загружаться PRICE и NOTE");
		foreach ($items as $id => &$item) {
			// Поведение ядра: без PROPERTIES массив полей будет заменён свойствами.
			if (isset($item["PROPERTIES"])) {
				$item["PROPERTIES"] = self::$items[$id]["PROPERTIES"];
			} else {
				$item = self::$items[$id]["PROPERTIES"];
			}
		}
		unset($item);
	}
	public static function GetElementGroups($ids, $onlyElements, $select): PriceRows
	{
		check($onlyElements === true, "Нужны только реальные привязки элементов к разделам");
		return new PriceRows(self::$groups);
	}
}

class CIBlock
{
	public static function GetPanelButtons(...$args): array { return []; }
	public static function GetArrayByID(...$args): string { return ""; }
}

class PriceTemplate
{
	public array $cacheKeys = [];
	public function getComponent(): self { return $this; }
	public function setResultCacheKeys(array $keys): void { $this->cacheKeys = array_merge($this->cacheKeys, $keys); }
	public function setFrameMode($value): void {}
	public function AddEditAction(...$args): void {}
	public function AddDeleteAction(...$args): void {}
	public function GetEditAreaId($id): string { return "price-test-" . $id; }
	public function render(array $arResult, array $arParams): string
	{
		ob_start();
		include __DIR__ . "/../local/templates/littleweb/components/bitrix/news/prices/bitrix/catalog.section.list/prices/template.php";
		return ob_get_clean();
	}
}

function buildPrices(array $sections, array $currentSection = []): array
{
	$arResult = ["SECTIONS" => $sections, "SECTION" => $currentSection];
	$arParams = ["IBLOCK_ID" => 22, "DETAIL_URL" => "/prices/#SECTION_CODE#/#ELEMENT_CODE#/", "IS_SECTION" => $currentSection ? "Y" : "N"];
	include __DIR__ . "/../local/templates/littleweb/components/bitrix/news/prices/bitrix/catalog.section.list/prices/result_modifier.php";
	return $arResult;
}

$sections = [];
$names = ["Юридические услуги", "Регистрация и ликвидация", "Кадровые услуги", "Бухгалтерские услуги", "Сопровождение сделок", "Персонал и бизнес-услуги"];
foreach ($names as $index => $name) {
	$parent = ($index + 1) * 10;
	$sections[] = ["ID" => $parent, "NAME" => $name, "DEPTH_LEVEL" => 1, "SECTION_PAGE_URL" => "/prices/direction-" . $parent . "/"];
	foreach ([1 => "Консультации и сопровождение", 2 => "Оформление документов", 3 => "Экспертиза", 4 => "Сопровождение", 5 => "Дополнительные услуги"] as $offset => $groupName) {
		$section = $parent + $offset;
		$sections[] = ["ID" => $section, "NAME" => $groupName, "DEPTH_LEVEL" => 2, "IBLOCK_SECTION_ID" => $parent];
		for ($i = 1; $i <= 3; $i++) {
			$id = $section * 10 + $i;
			CIBlockElement::$items[$id] = [
				"ID" => $id, "IBLOCK_ID" => 22, "IBLOCK_SECTION_ID" => $section,
				"CODE" => "price-" . $id,
				"NAME" => [1 => "Устная консультация специалиста", 2 => "Письменная консультация с алгоритмом действий", 3 => "Абонентское обслуживание организации"][$i],
				"PROPERTIES" => [
					"PRICE" => ["VALUE" => [1 => "1000", 2 => "от 2 500 ₽", 3 => ""][$i]],
					"NOTE" => ["VALUE" => $i === 1 ? "Стоимость указана за одну консультацию.\nИтоговая цена зависит от объёма задачи." : ""],
				],
			];
			CIBlockElement::$groups[] = ["ID" => $section, "IBLOCK_ELEMENT_ID" => $id];
		}
	}
}
$sections[] = ["ID" => 70, "NAME" => "Пустой раздел", "DEPTH_LEVEL" => 1];
$sections[] = ["ID" => 71, "NAME" => "Пустой подраздел", "DEPTH_LEVEL" => 2, "IBLOCK_SECTION_ID" => 70];
CIBlockElement::$items[112]["PROPERTIES"]["PRICE"]["VALUE"] = "0";
CIBlockElement::$items[121]["PROPERTIES"]["PRICE"]["VALUE"] = "1 234,50";
CIBlockElement::$items[122]["~NAME"] = "Консультация <директора> & бухгалтера";
CIBlockElement::$items[122]["PROPERTIES"]["NOTE"]["VALUE"] = ["TEXT" => "<b>Уточнение</b> &amp; условия", "TYPE" => "html"];
CIBlockElement::$groups[] = ["ID" => 21, "IBLOCK_ELEMENT_ID" => 111];
CIBlockElement::$groups[] = ["ID" => 21, "IBLOCK_ELEMENT_ID" => 111];
CIBlockElement::$groups[] = ["ID" => 999, "IBLOCK_ELEMENT_ID" => 111];

$result = buildPrices($sections);
$categories = $result["PRICE_CATEGORIES"];
check(count($categories) === 6, "Пустые разделы не должны попадать в фильтр");
check(count($categories[0]["GROUPS"]) === 5, "Нарушена группировка подразделов");
check($categories[0]["GROUPS"][0]["ITEMS"][0]["PRICE"] === "1 000 ₽", "Неверный формат числовой цены");
check($categories[0]["GROUPS"][0]["ITEMS"][1]["PRICE"] === "0 ₽", "Нулевая цена не должна исчезать");
check($categories[0]["GROUPS"][0]["ITEMS"][2]["PRICE"] === "индивидуально", "Отсутствует цена по запросу");
check($categories[0]["GROUPS"][1]["ITEMS"][0]["PRICE"] === "1 234,50 ₽", "Неверная обработка дробной цены");
check($categories[1]["GROUPS"][0]["ITEMS"][2]["PRICE"] === "от 2 500 ₽", "Текстовая цена должна сохраняться");
check(count($categories[1]["GROUPS"][0]["ITEMS"]) === 4, "Множественная привязка потеряна или продублирована");
check($categories[0]["GROUPS"][1]["ITEMS"][1]["NOTE"] === "Уточнение & условия", "Не обработано HTML-уточнение");

$html = (new PriceTemplate())->render($result, ["IBLOCK_ID" => 22]);
check(!str_contains($html, " open>"), "На разводной странице все подразделы должны быть закрыты");
check(!str_contains($html, 'class="price-category__link"') && str_contains($html, 'class="price-category__name"'), "На разводной странице названия позиций должны быть обычным текстом");
check(str_contains($html, "&lt;директора&gt; &amp; бухгалтера"), "Название должно экранироваться");
check(substr_count($html, 'class="price-category__group"') === 24, "В карточке должно быть максимум четыре подраздела");
check(substr_count($html, "Все цены раздела") === 6, "В каждой карточке нужна ссылка на полный прайс");
check(str_contains($html, 'href="/prices/direction-10/"'), "Ссылка должна вести на страницу направления");
check(!str_contains($html, "Дополнительные услуги"), "Пятый подраздел должен быть только на полной странице");
check(str_contains($html, "Устная консультация специалиста"), "Загрузка свойств не должна стирать название");
check(str_contains($html, "data-prices-prev") && str_contains($html, "data-prices-next"), "Слайдеру нужны обе стрелки");
$sections[0]["~DESCRIPTION"] = "<h2>О направлении</h2><p>Подробное описание &amp; условия.</p>";
$sections[0]["DESCRIPTION_TYPE"] = "html";
$sectionResult = buildPrices(array_values(array_filter($sections, static fn($section) => ($section["IBLOCK_SECTION_ID"] ?? 0) === 10)), $sections[0]);
$sectionHtml = (new PriceTemplate())->render($sectionResult, ["IBLOCK_ID" => 22, "IS_SECTION" => "Y", "GROUPS_LIMIT" => 0]);
check(substr_count($sectionHtml, 'class="price-category__group"') === 5, "На странице направления должны быть все подразделы");
check(str_contains($sectionHtml, '>Юридические услуги</h1>'), "Заголовок страницы должен соответствовать направлению");
check(!str_contains($sectionHtml, "data-prices-filters") && !str_contains($sectionHtml, "Все цены раздела"), "На полной странице не нужны фильтры и ссылка");
check(str_contains($sectionHtml, "Дополнительные услуги"), "Потерян пятый подраздел");
check(substr_count($sectionHtml, " open>") === 1, "На странице раздела первый пункт должен остаться раскрытым");
check(!preg_match('/<details[^>]+\bname=/', $sectionHtml), "Пункты полного прайса должны открываться независимо");
check(str_contains($sectionHtml, $sections[0]["~DESCRIPTION"]), "HTML-описание должно сохранять разметку");
check(strpos($sectionHtml, 'prices-catalog__section-description') > strrpos($sectionHtml, '</article>'), "Описание должно находиться ниже прайса");
check(!str_contains($html, 'prices-catalog__section-description'), "Описание раздела не нужно на разводной странице");

$GLOBALS['testLinkedServiceSections'][$sections[0]['ID']] = ['ID' => 666, 'NAME' => 'Юридические услуги', 'URL' => '/lawyer/'];
$linkedResult = buildPrices(array_values(array_filter($sections, static fn($section) => ($section['IBLOCK_SECTION_ID'] ?? 0) === 10)), $sections[0]);
$linkedTemplate = new PriceTemplate();
$linkedHtml = $linkedTemplate->render($linkedResult, ['IBLOCK_ID' => 22, 'IS_SECTION' => 'Y', 'GROUPS_LIMIT' => 0]);
check(in_array('SERVICE_SECTION_LINK', $linkedTemplate->cacheKeys, true), 'Связанное направление должно сохраняться для эпилога при попадании в кеш');
check(str_contains($linkedHtml, 'data-linked-service-section href="/lawyer/"'), 'Страница прайса должна ссылаться на связанное направление услуг');
check(!str_contains($sectionHtml, 'data-linked-service-section') && !str_contains($html, 'data-linked-service-section'), 'Без связи кнопка не должна выводиться');
unset($GLOBALS['testLinkedServiceSections']);
$textResult = $sectionResult;
$textResult["SECTION"]["DESCRIPTION_TYPE"] = "text";
$textResult["SECTION"]["~DESCRIPTION"] = "Стоимость <работ> & сроки.\nВторая строка.";
$textHtml = (new PriceTemplate())->render($textResult, ["IBLOCK_ID" => 22, "IS_SECTION" => "Y", "GROUPS_LIMIT" => 0]);
check(str_contains($textHtml, "Стоимость &lt;работ&gt; &amp; сроки.<br />\nВторая строка."), "Текстовое описание должно экранироваться с сохранением переносов");
$textResult["SECTION"]["~DESCRIPTION"] = "  ";
$emptyDescriptionHtml = (new PriceTemplate())->render($textResult, ["IBLOCK_ID" => 22, "IS_SECTION" => "Y", "GROUPS_LIMIT" => 0]);
check(!str_contains($emptyDescriptionHtml, 'prices-catalog__section-description'), "Пустое описание не должно создавать блок");
check(buildPrices($sections, ["ID" => 0, "DEPTH_LEVEL" => 0])["PRICE_CATEGORIES"] === [], "Неизвестный раздел не должен показывать весь каталог");
preg_match_all('/\bid="([^"]+)"/', $html, $ids);
check(count($ids[1]) === count(array_unique($ids[1])), "Идентификаторы элементов должны быть уникальными при множественной привязке");

$queryCount = CIBlockElement::$queries;
check(buildPrices([])["PRICE_CATEGORIES"] === [], "Пустой инфоблок должен обрабатываться без ошибок");
check(CIBlockElement::$queries === $queryCount, "Без подразделов не нужно запрашивать элементы");

if (in_array("--render", $argv, true)) {
	echo in_array("--section", $argv, true) ? $sectionHtml : $html;
} else {
	echo "OK: группировка, множественные привязки, цены, уточнения, ссылки, аккордеоны, экранирование и пустое состояние\n";
}
