<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

$this->setFrameMode(true);

if (empty($arResult["ITEMS"])) {
	return;
}

$block = $arResult["INDUSTRIES_BLOCK"];
$picture = $block["PICTURE"];
?>
<section class="section section--bg-initial industries">
	<div class="container">
		<div class="industries__layout<?= $picture ? " industries__layout--with-picture" : "" ?>">
			<div class="industries__content">
				<div class="industries__heading">
					<div class="eyebrow">расскажем</div>
					<?php if ($block["NAME"] !== ""): ?>
						<h2 class="section-title"><?= htmlspecialcharsbx($block["NAME"]) ?></h2>
					<?php endif; ?>
				</div>
				<?php if ($block["DESCRIPTION"] !== ""): ?>
					<div class="industries__description"><?= $block["DESCRIPTION_TYPE"] === "html" ? $block["DESCRIPTION"] : nl2br(htmlspecialcharsbx($block["DESCRIPTION"])) ?></div>
				<?php endif; ?>
				<ul class="industries__list">
					<?php foreach ($arResult["ITEMS"] as $item): ?>
						<?php
						$this->AddEditAction($item["ID"], $item["EDIT_LINK"], CIBlock::GetArrayByID($item["IBLOCK_ID"], "ELEMENT_EDIT"));
						$this->AddDeleteAction($item["ID"], $item["DELETE_LINK"], CIBlock::GetArrayByID($item["IBLOCK_ID"], "ELEMENT_DELETE"), ["CONFIRM" => GetMessage("CT_BNL_ELEMENT_DELETE_CONFIRM")]);
						?>
						<li class="industries__item" id="<?= $this->GetEditAreaId($item["ID"]) ?>">
							<svg width="24" height="24" aria-hidden="true" focusable="false"><use xlink:href="<?= SITE_TEMPLATE_PATH ?>/_dist/sprite.svg#icon-star"></use></svg>
							<span><?= htmlspecialcharsbx($item["NAME"]) ?></span>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
			<?php if ($picture): ?>
				<div class="industries__visual">
					<img src="<?= htmlspecialcharsbx($picture["SRC"]) ?>" width="<?= (int)$picture["WIDTH"] ?>" height="<?= (int)$picture["HEIGHT"] ?>" alt="" loading="lazy">
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
