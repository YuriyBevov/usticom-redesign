<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

$this->setFrameMode(true);

$faqItems = [];
foreach ($arResult["ITEMS"] ?? [] as $item) {
	$answer = trim((string)($item["~PREVIEW_TEXT"] ?? $item["PREVIEW_TEXT"] ?? ""));
	$answerType = (string)($item["PREVIEW_TEXT_TYPE"] ?? "text");
	if ($answer === "") {
		$answer = trim((string)($item["~DETAIL_TEXT"] ?? $item["DETAIL_TEXT"] ?? ""));
		$answerType = (string)($item["DETAIL_TEXT_TYPE"] ?? "text");
	}
	if ($answer !== "") {
		$faqItems[] = ["ITEM" => $item, "ANSWER" => $answer, "TYPE" => $answerType];
	}
}

if (!$faqItems) {
	return;
}

$description = trim((string)($arParams["FAQ_DESCRIPTION"] ?? ""));
$descriptionType = (string)($arParams["FAQ_DESCRIPTION_TYPE"] ?? "text");
?>
<section class="section section--bg-initial faq-section">
	<div class="container faq-section__grid">
		<div class="faq-section__intro">
			<div class="section-header">
				<div class="eyebrow">вопросы и ответы</div>
				<h2 class="section-title">Частые вопросы</h2>
			</div>
			<?php if ($description !== ""): ?>
				<div class="faq-section__description"><?= $descriptionType === "html" ? $description : nl2br(htmlspecialcharsbx($description)) ?></div>
			<?php endif; ?>
		</div>

		<div class="faq-list" data-faq-list itemscope itemtype="https://schema.org/FAQPage">
			<?php foreach ($faqItems as $index => $faq):
				$item = $faq["ITEM"];
				$this->AddEditAction($item["ID"], $item["EDIT_LINK"], CIBlock::GetArrayByID($item["IBLOCK_ID"], "ELEMENT_EDIT"));
				$this->AddDeleteAction($item["ID"], $item["DELETE_LINK"], CIBlock::GetArrayByID($item["IBLOCK_ID"], "ELEMENT_DELETE"), ["CONFIRM" => GetMessage("CT_BNL_ELEMENT_DELETE_CONFIRM")]);
				$panelId = "faq-panel-" . $this->GetEditAreaId($item["ID"]);
				$isOpen = $index === 1;
			?>
				<div class="faq-list__item<?= $isOpen ? " is-open" : "" ?>" id="<?= $this->GetEditAreaId($item["ID"]) ?>" itemprop="mainEntity" itemscope itemtype="https://schema.org/Question">
					<button class="faq-list__button" type="button" aria-expanded="<?= $isOpen ? "true" : "false" ?>" aria-controls="<?= htmlspecialcharsbx($panelId) ?>" data-faq-button>
						<span class="faq-list__question" itemprop="name"><?= htmlspecialcharsbx($item["~NAME"] ?? $item["NAME"]) ?></span>
						<svg class="faq-list__toggle" width="24" height="24" aria-hidden="true" focusable="false">
							<use xlink:href="<?= SITE_TEMPLATE_PATH ?>/_dist/sprite.svg#icon-chevron-down"></use>
						</svg>
					</button>
					<div class="faq-list__content" id="<?= htmlspecialcharsbx($panelId) ?>" itemprop="acceptedAnswer" itemscope itemtype="https://schema.org/Answer" data-faq-content<?= $isOpen ? "" : " hidden" ?>>
						<div itemprop="text"><?= $faq["TYPE"] === "html" ? $faq["ANSWER"] : nl2br(htmlspecialcharsbx($faq["ANSWER"])) ?></div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
