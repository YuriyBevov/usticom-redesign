<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$mobile = ($arParams['VIEW_MODE'] ?? '') === 'mobile';
$prefix = $mobile ? 'mobile-services' : 'desktop-services';
$visibleSections = $mobile ? $arResult : array_slice($arResult, 0, 5);
$moreSections = $mobile ? [] : array_slice($arResult, 5);
$moreSelected = false;
foreach ($moreSections as $section) {
    if ($section['SELECTED']) $moreSelected = true;
}
?>
<nav class="nav <?= $mobile ? 'mobile-menu__services' : 'header__nav' ?>" aria-label="<?= usticomText('Услуги') ?>">
    <ul class="nav__list">
        <?php foreach ($visibleSections as $index => $section): ?>
            <?php $submenuId = $prefix . '-' . (int)$section['PARAMS']['SECTION_ID']; ?>
            <li class="nav__list-item<?= $section['SELECTED'] ? ' is-current' : '' ?><?= $section['CHILDREN'] ? ' nav__list-item--parent' : '' ?>">
                <div class="nav__row">
                    <a class="nav__link" href="<?= htmlspecialcharsbx($section['LINK']) ?>"<?= $section['SELECTED'] ? ' aria-current="true"' : '' ?><?= !$mobile && $section['CHILDREN'] ? ' aria-expanded="false" aria-controls="' . $submenuId . '"' : '' ?>><?= htmlspecialcharsbx($section['TEXT']) ?></a>
                    <?php if ($mobile && $section['CHILDREN']): ?>
                        <button class="nav__toggle" type="button" aria-label="<?= htmlspecialcharsbx(usticomIsEnglish() ? 'Services: '.$section['TEXT'] : 'Услуги раздела «' . $section['TEXT'] . '»') ?>" aria-expanded="false" aria-controls="<?= $submenuId ?>">
                            <svg width="14" height="8" aria-hidden="true"><use href="<?= SITE_TEMPLATE_PATH ?>/_dist/sprite.svg#icon-chevron-down"></use></svg>
                        </button>
                    <?php endif; ?>
                </div>
                <?php if ($section['CHILDREN']): ?>
                    <div class="nav__dropdown" id="<?= $submenuId ?>"<?= $mobile ? ' hidden' : '' ?>>
                        <div class="nav__columns">
                            <?php $columns = $mobile ? [$section['CHILDREN']] : array_chunk($section['CHILDREN'], (int)ceil(count($section['CHILDREN']) / 4)); ?>
                            <?php foreach ($columns as $column): ?>
                                <ul class="nav__sublist">
                                    <?php foreach ($column as $child): ?>
                                        <li><a class="nav__sublink" href="<?= htmlspecialcharsbx($child['LINK']) ?>"<?= $child['SELECTED'] ? ' aria-current="page"' : '' ?>><?= htmlspecialcharsbx($child['TEXT']) ?></a></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endforeach; ?>
                        </div>
                        <?php if (!$mobile): ?>
                            <div class="nav__promotion">
                                <div><strong><?= usticomText('Не знаете с чего начать? Мы поможем.') ?></strong><span><?= usticomText('Ответим за 15 мин.') ?></span></div>
                                <a class="main-btn header__request" data-form-id="<?= usticomFormId(2) ?>" data-form-theme="<?= usticomText('Оставить заявку') ?>" data-header-request href="<?= usticomPopupUrl(2) ?>"><?= usticomText('Оставить заявку') ?></a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
        <?php if ($moreSections): ?>
            <li class="nav__list-item nav__list-item--parent nav__list-item--more<?= $moreSelected ? ' is-current' : '' ?>">
                <div class="nav__row">
                    <button class="nav__link nav__more-toggle" type="button" aria-expanded="false" aria-controls="desktop-services-more"><?= usticomText('Ещё...') ?></button>
                </div>
                <div class="nav__dropdown nav__dropdown--more" id="desktop-services-more">
                    <ul class="nav__sublist">
                        <?php foreach ($moreSections as $section): ?>
                            <li><a class="nav__sublink" href="<?= htmlspecialcharsbx($section['LINK']) ?>"<?= $section['SELECTED'] ? ' aria-current="true"' : '' ?>><?= htmlspecialcharsbx($section['TEXT']) ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </li>
        <?php endif; ?>
    </ul>
</nav>
