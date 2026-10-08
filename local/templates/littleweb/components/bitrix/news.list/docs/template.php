<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

$this->setFrameMode(true);
if (empty($arResult["DOCUMENTS"])) {
	return;
}

$sectionTitle = (string)($arParams["SECTION_TITLE"] ?? "Документы для клиентов");
$sectionText = (string)($arParams["SECTION_TEXT"] ?? "");
?>

<section class="section section--bg-initial docs">
	<div class="container">
		<?php $APPLICATION->IncludeFile(
			SITE_TEMPLATE_PATH . "/include/section-header.php",
			[
				"EYEBROW_TEXT" => $arParams["EYEBROW_TEXT"] ?? "документы",
				"TITLE" => htmlspecialcharsbx($sectionTitle),
				"TEXT" => htmlspecialcharsbx($sectionText),
				"USE_SWIPER_NAVIGATION" => "N",
			],
			["MODE" => "html", "NAME" => "Шапка документов", "SHOW_BORDER" => true]
		); ?>

		<div class="docs__grid">
			<?php foreach ($arResult["DOCUMENTS"] as $item):
				$this->AddEditAction($item["ID"], $item["EDIT_LINK"] ?? "", CIBlock::GetArrayByID($item["IBLOCK_ID"], "ELEMENT_EDIT"));
				$this->AddDeleteAction($item["ID"], $item["DELETE_LINK"] ?? "", CIBlock::GetArrayByID($item["IBLOCK_ID"], "ELEMENT_DELETE"), ["CONFIRM" => GetMessage("CT_BNL_ELEMENT_DELETE_CONFIRM")]);
				$preview = trim((string)($item["~PREVIEW_TEXT"] ?? $item["PREVIEW_TEXT"] ?? ""));
			?>
				<article class="doc-card" id="<?= $this->GetEditAreaId($item["ID"]) ?>">
					<span class="doc-card__extension" aria-hidden="true"><?= htmlspecialcharsbx($item["DOCUMENT_EXTENSION"] ?: "FILE") ?></span>
					<div class="doc-card__body">
						<span class="doc-card__title"><?= htmlspecialcharsbx($item["~NAME"] ?? $item["NAME"]) ?></span>
						<?php if ($preview !== ""): ?>
							<div class="doc-card__preview-text"><?= ($item["PREVIEW_TEXT_TYPE"] ?? "text") === "html" ? $preview : nl2br(htmlspecialcharsbx($preview)) ?></div>
						<?php endif; ?>
						<a class="doc-card__download" href="<?= htmlspecialcharsbx($item["DOCUMENT_FILE"]["SRC"]) ?>" download="<?= htmlspecialcharsbx($item["DOCUMENT_FILE"]["ORIGINAL_NAME"] ?: $item["DOCUMENT_FILE"]["FILE_NAME"]) ?>" aria-label="<?= htmlspecialcharsbx("Скачать: " . ($item["~NAME"] ?? $item["NAME"])) ?>">
							Скачать
							<svg width="12" height="10" aria-hidden="true"><use xlink:href="<?= SITE_TEMPLATE_PATH ?>/_dist/sprite.svg#icon-arrow"></use></svg>
						</a>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
