<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$this->setFrameMode(true);

$strSectionEdit = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_EDIT");
$strSectionDelete = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_DELETE");
$arSectionDeleteParams = array("CONFIRM" => GetMessage('CT_BCSL_ELEMENT_DELETE_CONFIRM'));
?>

<section class="lw-section team">
	<div class="lw-container-thin">
		<div class="team-tabs">
			<? foreach ($arResult["SECTIONS"] as $key => $arSection): ?>
				<div class="team-tabs__item <?= ($key == 0 ? 'active' : null) ?>">
					<button class="team-tabs__opener" type="button">
						<span class="lw-section-title"><?= $arSection["NAME"] ?></span>
						<svg width="32" height="32">
							<use xlink:href="<?= SITE_TEMPLATE_PATH ?>/assets/sprite.svg#icon-lg-arrow" />
						</svg>
					</button>

					<div class="team-tabs__content">
						<ul class="team__list">
							<?
							/*Формируем массив */
							$arSelect = array("ID", "IBLOCK_ID", "NAME", "PREVIEW_TEXT", "PREVIEW_PICTURE", "DETAIL_PICTURE");
							$arFilter = array("IBLOCK_ID" => $arResult["ID"], "SECTION_ID" => $arSection["ID"]);
							$res = CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);

							while ($ob = $res->GetNextElement()):
								$arFields = $ob->GetFields();
								$arProps = $ob->GetProperties();

								$resImage = CFile::ResizeImageGet(
									$arFields["PREVIEW_PICTURE"],
									array("width" => 360, "height" => 480),
									BX_RESIZE_IMAGE_EXACT
								);

								// debug($resImage);
							?>

								<li class="team__list-item">
									<div class="staff-card staff-card--short">
										<div class="lw-image-hovered-wrapper">
											<img src="<?= $resImage['src'] ?>" alt="<?= $arFields["NAME"] ?>" width="250" height="285">
										</div>
										<div class="staff-card__content">
											<span class="staff-card__title"><?= $arFields["NAME"] ?></span>
											<span class="staff-card__desc"><?= $arFields["PREVIEW_TEXT"] ?></span>

											<template id="staff-card-full-description">
												<!--HTML-TEMPLATE-->
												<? if (!empty($arProps["STAFF_SPECIALIZATIONS"]["VALUE"])): ?>
													<ul class="staff-card__list">
														<? foreach ($arProps["STAFF_SPECIALIZATIONS"]["VALUE"] as $arSpec): ?>
															<li class="staff-card__list-item"><?= $arSpec ?></li>
														<? endforeach; ?>
													</ul>
												<? endif; ?>
											</template>

											<button class="lw-main-btn lw-main-btn--accent staff-card__btn" type="button" data-modal-anchor="staff-card">Подробнее</button>
										</div>
									</div>
								</li>

							<? endwhile; ?>
						</ul>
					</div>
				</div>
			<? endforeach; ?>
		</div>

		<div class="modal staff-card-modal" id="staff-card">
			<div class="modal__overlay">
				<div class="modal__content">
					<button class="modal-closer">
						<svg width="24" height="24">
							<use xlink:href="<?= SITE_TEMPLATE_PATH ?>/assets/sprite.svg#icon-close"></use>
						</svg>
					</button>

					<div class="modal__content-body">
						<div class="staff-card" style="width:100%;">
							<div class="lw-image-hovered-wrapper">
								<img
									src=""
									alt="" width="250" height="285"
									style="height:480px;">
							</div>

							<div class="staff-card__content">
								<span class="staff-card__title"><!--JS--></span>
								<span class="staff-card__desc"><!--JS--></span>

								<span class="staff-card__list-title">Специализация:</span>
								<ul class="staff-card__list">
									<!--JS-->
								</ul>

								<? $APPLICATION->IncludeComponent(
									"bitrix:main.include",
									"",
									array(
										"AREA_FILE_SHOW" => "file",
										"PATH" => SITE_TEMPLATE_PATH . "/include/staff-company-phone.php"
									),
									false,
									array()
								); ?>

							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

	</div>
</section>