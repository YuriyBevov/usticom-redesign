<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

$this->setFrameMode(true);

if (empty($arResult["ITEMS"])) {
	return;
}

$imagePath = SITE_TEMPLATE_PATH . "/_src/images/content-images/";
$flags = [
	"армения" => "armenia",
	"великобритания" => "united-kingdom",
	"италия" => "italy",
	"казахстан" => "kazakhstan",
	"киргизия" => "kyrgyzstan",
	"кыргызстан" => "kyrgyzstan",
	"узбекистан" => "uzbekistan",
	"турецкая республика" => "turkey",
	"турция" => "turkey",
	"китайская народная республика" => "china",
	"китай" => "china",
	"объединенные арабские эмираты" => "uae",
	"объединённые арабские эмираты" => "uae",
	"оаэ" => "uae",
	"сербия" => "serbia",
	"оман" => "oman",
	"гонконг" => "hong-kong",
];
$description = trim((string)($arResult["~DESCRIPTION"] ?? $arResult["DESCRIPTION"] ?? ""));
$descriptionType = (string)($arResult["DESCRIPTION_TYPE"] ?? "text");
if ($description === "") {
	$description = "Мы развиваем международное сотрудничество и выстраиваем долгосрочные партнерские отношения с компаниями по всему миру.";
}
?>
<section class="section section--bg-initial partnership">
	<div class="container partnership__layout">
		<div class="partnership__intro">
			<div class="section-header partnership__header">
				<div class="eyebrow">международное сотрудничество</div>
				<h2 class="section-title">С какими странами мы работаем</h2>
			</div>
			<div class="partnership__description"><?= $descriptionType === "html" ? $description : nl2br(htmlspecialcharsbx($description)) ?></div>
			<div class="partnership__visual">
				<img src="<?= $imagePath ?>partnership-globe.png" width="596" height="596" alt="" loading="lazy">
			</div>
		</div>
		<ul class="partnership__countries">
			<?php foreach ($arResult["ITEMS"] as $item):
				$name = trim((string)($item["~NAME"] ?? $item["NAME"] ?? ""));
				if ($name === "") {
					continue;
				}
				$key = mb_strtolower(preg_replace("/\s+/u", " ", $name));
				$flag = isset($flags[$key]) ? $imagePath . "partnership-" . $flags[$key] . ".png" : (string)($item["PREVIEW_PICTURE"]["SRC"] ?? "");
				$this->AddEditAction($item["ID"], $item["EDIT_LINK"], CIBlock::GetArrayByID($item["IBLOCK_ID"], "ELEMENT_EDIT"));
				$this->AddDeleteAction($item["ID"], $item["DELETE_LINK"], CIBlock::GetArrayByID($item["IBLOCK_ID"], "ELEMENT_DELETE"), ["CONFIRM" => GetMessage("CT_BNL_ELEMENT_DELETE_CONFIRM")]);
			?>
				<li class="partnership__country" id="<?= $this->GetEditAreaId($item["ID"]) ?>">
					<?php if ($flag !== ""): ?><img src="<?= htmlspecialcharsbx($flag) ?>" width="40" height="40" alt="" loading="lazy"><?php endif; ?>
					<span><?= htmlspecialcharsbx($name) ?></span>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
