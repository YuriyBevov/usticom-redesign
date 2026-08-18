<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if (!empty($arResult)):
?>

	<div class="bottom-menu<?= ($arParams["DIRECTION"] ? ' bottom-menu--' . $arParams["DIRECTION"] : '') ?>">
		<ul>

			<? foreach ($arResult as $arItem):
				if ($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1)
					continue;

				$linkClass = $arItem["SELECTED"] ? 'selected-link' : '';
				$linkClass .= $arItem["PARAMS"]["COLOR"] === 'ACCENT' ? ' accent-link' : '';
			?>
				<li>
					<a href="<?= $arItem["LINK"] ?>" class="<?= $linkClass ?>">
						<?= $arItem["TEXT"] ?>
					</a>
				</li>
			<? endforeach ?>

		</ul>
	</div>
<? endif ?>