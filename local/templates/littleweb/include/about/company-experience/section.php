<section class="section section--bg-initial company-experience">
	<div class="container">
		<div class="company-experience__grid">
			<div class="company-experience__picture">
				<? $APPLICATION->IncludeFile(
					SITE_DIR . ltrim($arParams["IMG_PATH"], "/"),
					array(),
					array('MODE' => 'html', 'NAME' => 'Изображение', 'SHOW_BORDER' => true)
				); ?>
			</div>

			<div class="company-experience__content">
				<div class="eyebrow">
					<? $APPLICATION->IncludeFile(
						SITE_DIR . ltrim($arParams["EYEBROW_PATH"], "/"),
						array(),
						array('MODE' => 'html', 'NAME' => 'Надпись над заголовком', 'SHOW_BORDER' => true)
					); ?>
				</div>

				<div class="company-experience__main">
					<h2 class="company-experience__title">
						<? $APPLICATION->IncludeFile(
							SITE_DIR . ltrim($arParams["TITLE_PATH"], "/"),
							array(),
							array('MODE' => 'html', 'NAME' => 'Заголовок', 'SHOW_BORDER' => true)
						); ?>
					</h2>

					<div class="company-experience__text">
						<? $APPLICATION->IncludeFile(
							SITE_DIR . ltrim($arParams["TEXT_PATH"], "/"),
							array(),
							array('MODE' => 'html', 'NAME' => 'Текст', 'SHOW_BORDER' => true)
						); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
