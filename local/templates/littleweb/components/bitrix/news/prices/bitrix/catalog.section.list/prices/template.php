<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}
$this->setFrameMode(true);
$categories = $arResult["PRICE_CATEGORIES"];
$isSection = ($arParams["IS_SECTION"] ?? "N") === "Y";
$groupsLimit = max(0, (int)($arParams["GROUPS_LIMIT"] ?? 4));
$title = $isSection ? (string)($arResult["SECTION"]["~NAME"] ?? $arResult["SECTION"]["NAME"] ?? "") : "Цены на услуги";
$description = $isSection ? trim((string)($arResult["SECTION"]["~DESCRIPTION"] ?? $arResult["SECTION"]["DESCRIPTION"] ?? "")) : "";
$descriptionType = strtolower((string)($arResult["SECTION"]["DESCRIPTION_TYPE"] ?? "text"));
$catalogId = "prices-" . $this->GetEditAreaId((int)$arParams["IBLOCK_ID"]);
?>

<section class="section prices-catalog<?= $isSection ? " prices-catalog--section" : "" ?>" data-prices-catalog aria-labelledby="<?= htmlspecialcharsbx($catalogId) ?>-title">
	<div class="container prices-catalog__inner">
		<div class="section-header">
			<div class="eyebrow">прайс-лист</div>
			<h1 class="section-title" id="<?= htmlspecialcharsbx($catalogId) ?>-title"><?= htmlspecialcharsbx($title) ?></h1>
		</div>
		<p class="prices-catalog__description">Ориентировочная стоимость услуг «Юстиком» для бизнеса и частных лиц. Точный перечень и стоимость работ закрепляются в договоре — цена зависит от срочности, сложности и объёма задачи.</p>
		<?php if ($categories): ?>
			<?php if (!$isSection): ?>
				<div class="prices-catalog__directions" data-prices-directions hidden>
					<button class="swiper-button swiper-button--prev" type="button" data-prices-prev aria-label="Предыдущие направления" aria-controls="<?= htmlspecialcharsbx($catalogId) ?>-filters">
						<svg width="24" height="12" aria-hidden="true"><use href="<?= SITE_TEMPLATE_PATH ?>/_dist/sprite.svg#icon-arrow"></use></svg>
					</button>
					<div class="prices-catalog__filters swiper" id="<?= htmlspecialcharsbx($catalogId) ?>-filters" role="group" aria-label="Направления услуг" data-prices-filters>
						<div class="swiper-wrapper">
						<div class="swiper-slide"><a class="prices-catalog__filter is-active" href="<?= htmlspecialcharsbx($arParams["IBLOCK_URL"] ?? "/prices/") ?>" aria-current="page">Все</a></div>
						<?php foreach ($categories as $category):
							$filterName = preg_replace('/(?<!\p{L})услуги(?!\p{L})/iu', '', $category["NAME"]);
							$filterName = trim(preg_replace('/\s+/u', ' ', preg_replace('/[\s–—-]+$/u', '', $filterName)));
						?>
							<div class="swiper-slide"><a class="prices-catalog__filter" href="<?= htmlspecialcharsbx($category["URL"]) ?>"><?= htmlspecialcharsbx($filterName) ?></a></div>
						<?php endforeach; ?>
						</div>
					</div>
					<button class="swiper-button swiper-button--next" type="button" data-prices-next aria-label="Следующие направления" aria-controls="<?= htmlspecialcharsbx($catalogId) ?>-filters">
						<svg width="24" height="12" aria-hidden="true"><use href="<?= SITE_TEMPLATE_PATH ?>/_dist/sprite.svg#icon-arrow"></use></svg>
					</button>
				</div>
			<?php endif; ?>
			<div class="prices-catalog__grid" data-prices-grid id="<?= htmlspecialcharsbx($catalogId) ?>-grid">
				<?php foreach ($categories as $category): ?>
					<article class="price-category" data-price-category="<?= $category["ID"] ?>">
						<?php if (!$isSection): ?><h2 class="price-category__title"><?= htmlspecialcharsbx($category["NAME"]) ?></h2><?php endif; ?>
						<div class="price-category__groups">
							<?php foreach ($groupsLimit ? array_slice($category["GROUPS"], 0, $groupsLimit) : $category["GROUPS"] as $index => $group): ?>
								<details class="price-category__group"<?= !$isSection ? ' name="' . htmlspecialcharsbx($catalogId) . '-' . $category["ID"] . '"' : "" ?><?= $isSection && $index === 0 ? " open" : "" ?>>
									<summary class="price-category__summary">
										<span><?= htmlspecialcharsbx($group["NAME"]) ?></span>
										<svg width="16" height="16" aria-hidden="true" focusable="false"><use href="<?= SITE_TEMPLATE_PATH ?>/_dist/sprite.svg#icon-chevron-down"></use></svg>
									</summary>
									<ul class="price-category__items">
										<?php foreach ($group["ITEMS"] as $item):
											$editId = $group["ID"] . "-" . $item["ID"];
											$this->AddEditAction($editId, $item["EDIT_LINK"], CIBlock::GetArrayByID((int)$arParams["IBLOCK_ID"], "ELEMENT_EDIT"));
											$this->AddDeleteAction($editId, $item["DELETE_LINK"], CIBlock::GetArrayByID((int)$arParams["IBLOCK_ID"], "ELEMENT_DELETE"), ["CONFIRM" => "Удалить позицию прайса?"]);
										?>
											<li class="price-category__item" id="<?= $this->GetEditAreaId($editId) ?>">
												<div class="price-category__row">
													<?php if ($isSection): ?>
														<a class="price-category__link" href="<?= htmlspecialcharsbx($item["URL"]) ?>"><?= htmlspecialcharsbx($item["NAME"]) ?></a>
													<?php else: ?>
														<span class="price-category__name"><?= htmlspecialcharsbx($item["NAME"]) ?></span>
													<?php endif; ?>
													<span class="price-category__price<?= $item["HAS_PRICE"] ? "" : " price-category__price--individual" ?>"><?= htmlspecialcharsbx($item["PRICE"]) ?></span>
												</div>
												<?php if ($item["NOTE"] !== ""): ?><p class="price-category__note"><?= htmlspecialcharsbx($item["NOTE"]) ?></p><?php endif; ?>
											</li>
										<?php endforeach; ?>
									</ul>
								</details>
							<?php endforeach; ?>
						</div>
						<?php if (!$isSection): ?>
							<a class="price-category__all" href="<?= htmlspecialcharsbx($category["URL"]) ?>">Все цены раздела <span aria-hidden="true">→</span></a>
						<?php endif; ?>
					</article>
				<?php endforeach; ?>
			</div>
		<?php else: ?>
			<p class="prices-catalog__empty">Прайс-лист обновляется. Уточните стоимость услуг, отправив заявку на консультацию.</p>
		<?php endif; ?>
		<?php if ($description !== ""): ?>
			<div class="prices-catalog__section-description content-block"><?= $descriptionType === "html" ? $description : nl2br(htmlspecialcharsbx($description)) ?></div>
		<?php endif; ?>
		<div class="prices-catalog__notice">
			<img src="<?= SITE_TEMPLATE_PATH ?>/components/bitrix/news/prices/images/info.svg" width="32" height="32" alt="" aria-hidden="true">
			<p>Цены указаны ориентировочно и могут меняться в зависимости от обстоятельств дела. Услуги нотариуса и государственная пошлина оплачиваются клиентом отдельно.</p>
		</div>
	</div>
</section>
