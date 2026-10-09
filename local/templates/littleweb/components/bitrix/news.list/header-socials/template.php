<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php if ($arResult['ITEMS']): ?>
    <ul class="header-socials" aria-label="Социальные сети">
        <?php foreach ($arResult['ITEMS'] as $item): ?>
            <li><a class="header-socials__link header-socials__link--<?= $item['ICON'] ?>" href="<?= htmlspecialcharsbx($item['URL']) ?>" aria-label="<?= htmlspecialcharsbx($item['NAME']) ?>" target="_blank" rel="nofollow noopener noreferrer">
                <svg width="24" height="24" aria-hidden="true"><use href="<?= SITE_TEMPLATE_PATH ?>/_dist/sprite.svg#icon-<?= in_array($item['ICON'], ['whatsapp', 'youtube'], true) ? 'header-' . $item['ICON'] : $item['ICON'] ?>"></use></svg>
            </a></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
