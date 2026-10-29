<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<nav class="nav mobile-menu__pages" aria-label="<?= usticomIsEnglish() ? 'Site pages' : 'О компании и события' ?>">
    <ul class="nav__list">
        <?php foreach ($arResult as $item): ?>
            <li class="nav__list-item"><a class="nav__link" href="<?= htmlspecialcharsbx($item['LINK']) ?>"<?= $item['SELECTED'] ? ' aria-current="page"' : '' ?>><?= htmlspecialcharsbx($item['TEXT']) ?></a></li>
        <?php endforeach; ?>
    </ul>
</nav>
