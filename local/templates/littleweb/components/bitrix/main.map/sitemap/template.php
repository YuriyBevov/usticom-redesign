<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

$renderChildren = static function (array $items) use (&$renderChildren): void {
    if (!$items) return;
    ?>
    <ul class="sitemap__links">
        <?php foreach ($items as $item): ?>
            <li>
                <a href="<?= htmlspecialcharsbx($item['FULL_PATH'], ENT_COMPAT, false) ?>"><?= htmlspecialcharsbx($item['NAME'], ENT_COMPAT, false) ?></a>
                <?php $renderChildren($item['CHILDREN'] ?? []); ?>
            </li>
        <?php endforeach; ?>
    </ul>
    <?php
};
?>

<nav class="sitemap" aria-label="Карта сайта">
    <div class="sitemap__overview">
        <h2 class="sitemap__heading">Разделы сайта</h2>
        <ul class="sitemap__pages">
            <?php foreach ($arResult['PAGES'] as $item): ?>
                <li><a href="<?= htmlspecialcharsbx($item['FULL_PATH'], ENT_COMPAT, false) ?>"><span><?= htmlspecialcharsbx($item['NAME'], ENT_COMPAT, false) ?></span><span class="sitemap__arrow" aria-hidden="true">↗</span></a></li>
            <?php endforeach; ?>
        </ul>
    </div>

    <?php if ($arResult['SERVICES']): ?>
        <div class="sitemap__services">
            <h2 class="sitemap__heading"><a href="<?= SITE_DIR ?>services/">Услуги компании</a></h2>
            <div class="sitemap__grid">
                <?php foreach ($arResult['SERVICES'] as $item): ?>
                    <div class="sitemap__group">
                        <h3 class="sitemap__group-title"><a href="<?= htmlspecialcharsbx($item['FULL_PATH'], ENT_COMPAT, false) ?>"><?= htmlspecialcharsbx($item['NAME'], ENT_COMPAT, false) ?></a></h3>
                        <?php $renderChildren($item['CHILDREN']); ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</nav>
