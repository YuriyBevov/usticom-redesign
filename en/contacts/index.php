<?php
require $_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php';
usticomSetPageMeta('contacts', 'Contacts');
?>
<section class="section"><div class="container">
    <div class="section-header"><div class="eyebrow">get in touch</div><h1 class="section-title"><?php $APPLICATION->ShowTitle(false); ?></h1></div>
    <?php foreach (['phone' => 'Phone', 'mail' => 'Email', 'address' => 'Address', 'worktime' => 'Office hours'] as $file => $label): ?>
        <h2><?= $label ?></h2>
        <?php $APPLICATION->IncludeFile(SITE_DIR.'include/'.$file.'.php', [], ['MODE' => 'html', 'SHOW_BORDER' => true]); ?>
    <?php endforeach; ?>
</div></section>
<?php require $_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php'; ?>
