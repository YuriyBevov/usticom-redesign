<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php if ($arResult): ?>
    <nav class="header-top__nav" aria-label="О компании и событиях">
        <ul class="header-top__list">
            <?php foreach ($arResult as $item): ?>
                <li><a class="header-top__link<?= $item['SELECTED'] ? ' is-current' : '' ?>" href="<?= htmlspecialcharsbx($item['LINK']) ?>"<?= $item['SELECTED'] ? ' aria-current="page"' : '' ?>><?= htmlspecialcharsbx($item['TEXT']) ?></a></li>
            <?php endforeach; ?>
        </ul>
    </nav>
<?php endif; ?>
