<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

$this->setFrameMode(true);
if (empty($arResult["WORKFLOW"])) {
	return;
}
?>
<section class="section section--bg-initial workflow">
	<div class="container">
		<ul class="workflow-list">
			<li class="workflow-list__heading">
				<div class="section-header">
					<div class="eyebrow">какие есть</div>
					<?php if ($arResult["WORKFLOW_MAIN_TITLE"] !== ""): ?>
						<h2 class="section-title"><?= htmlspecialcharsbx($arResult["WORKFLOW_MAIN_TITLE"]) ?></h2>
					<?php endif; ?>
				</div>
			</li>
			<?php foreach ($arResult["WORKFLOW"] as $index => $step):
				$number = sprintf("%02d", $index + 1);
			?>
				<li class="workflow-list__item">
					<span class="workflow-list__number" aria-hidden="true"><?= $number ?></span>
					<div class="workflow-list__content">
						<?php if ($step["TITLE"] !== ""): ?><h3><?= htmlspecialcharsbx($step["TITLE"]) ?></h3><?php endif; ?>
						<?php if ($step["TEXT"] !== ""): ?><p><?= nl2br(htmlspecialcharsbx($step["TEXT"])) ?></p><?php endif; ?>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
