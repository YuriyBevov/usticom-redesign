<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
?>

<? if ($arResult["ITEMS"]): ?>
	<div class="social">
		<ul class="social__list">
			<? foreach ($arResult["ITEMS"] as $arItem): ?>
				<li class="social__list-item">
					<a href="<?= ($arItem["PROPERTIES"]["SOCIAL_LINK"]["VALUE"] ? $arItem["PROPERTIES"]["SOCIAL_LINK"]["VALUE"] : '#') ?>" rel="nofollow noreferrer noopener">
						<?
						$arFile = CFile::GetFileArray($arItem["PROPERTIES"]["SOCIAL_ICON"]["VALUE"]);
						?>

						<? if ($arFile): ?>
							<img src="<?= $arFile["SRC"] ?>" alt="<?= $arItem["NAME"] ?>" width="<?= ($arFile["WIDTH"] ? $arFile["WIDTH"] : 24) ?>" height="<?= ($arFile["HEIGHT"] ? $arFile["HEIGHT"] : 24) ?>">
						<? endif;; ?>
					</a>
				</li>
			<? endforeach; ?>
		</ul>
	</div>
<? endif; ?>