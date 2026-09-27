<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

$this->setFrameMode(true);
if (empty($arResult["INCLUDES"])) {
	return;
}
?>
<section class="section section--bg-initial includes">
	<div class="container">
		<ul class="includes-list">
			<li class="includes-list__heading">
				<div class="section-header">
					<div class="eyebrow">узнайте о том</div>
					<?php if ($arResult["INCLUDES_MAIN_TITLE"] !== ""): ?>
						<h2 class="section-title"><?= htmlspecialcharsbx($arResult["INCLUDES_MAIN_TITLE"]) ?></h2>
					<?php endif; ?>
				</div>
			</li>
			<?php foreach ($arResult["INCLUDES"] as $index => $include):
				$text = $include["TEXT"] !== "" ? $include["TEXT"] : $include["TITLE"];
				$number = sprintf("%02d", $index + 1);
			?>
				<li class="includes-list__item">
					<p><?= nl2br(htmlspecialcharsbx($text)) ?></p>
					<span class="includes-list__number" aria-hidden="true"><?= $number ?></span>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
