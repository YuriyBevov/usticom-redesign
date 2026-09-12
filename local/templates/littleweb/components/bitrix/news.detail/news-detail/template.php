<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
$APPLICATION->AddHeadString('<meta property="og:image" content="https://' .  $_SERVER["SERVER_NAME"] . $arResult["DETAIL_PICTURE"]["SRC"] . '"/>', true);
?>

<section class="section news-detail">
	<div class="container">
		<div class="news-detail__header">
			<div class="news-detail__header-section">

				<? if (!empty($arResult["PROPERTIES"]["TAG_LIST"]["VALUE"])): ?>
					<div class="swiper tag-slider news-detail__tag-slider">
						<ul class="swiper-wrapper tag-list tag-list--slider">
							<? foreach ((array)$arResult["PROPERTIES"]["TAG_LIST"]["VALUE"] as $arTag): ?>
								<li class="swiper-slide tag-list__item">
									<a href="<?= htmlspecialcharsbx($arResult["LIST_PAGE_URL"] . '?tag=' . rawurlencode($arTag)) ?>">#<?= htmlspecialcharsbx($arTag) ?></a>
								</li>
							<? endforeach; ?>
						</ul>
					</div>
				<? endif; ?>
				<h1><?= $arResult["NAME"] ?></h1>
				<button class="main-btn">Получить консультацию</button>
			</div>
			<div class="news-detail__header-section">
				<img src="<?= $arResult["DETAIL_PICTURE"]["SRC"] ?>" alt="<?= $arResult["NAME"] ?>" width="680" height="340">
			</div>
		</div>
			<div class="news-detail__content content-block">
				<?= $arResult["DETAIL_TEXT"] ?>

				<div class="article-content">
					<div class="article-content__text">
						<p>Владельцы бизнеса дорожат своим временем. Поэтому стремятся максимально оптимизировать рабочие процессы. Кадровый аутсорсинг стал эффективным инструментом построения процветающей компании. Эта услуга помогает профессионально и комплексно организовать HR-процессы, снизить нагрузку на штатных специалистов, выстраивать бесконфликтные трудовые отношения с персоналом.</p>
						<p>Владельцы бизнеса дорожат своим временем. Поэтому стремятся максимально оптимизировать рабочие процессы. Кадровый аутсорсинг стал эффективным инструментом построения процветающей компании. Эта услуга помогает профессионально и комплексно организовать HR-процессы, снизить нагрузку на штатных специалистов, выстраивать бесконфликтные трудовые отношения с персоналом.</p>
					</div>

					<aside class="article-footnote">
						<p><span class="article-footnote__title">Сноски</span>«Юстиком» занимается аутсорсингом кадровых услуг более 18 лет. В нашей команде трудятся настоящие эксперты и фанаты своего дела. Мы предлагаем полный спектр услуг по найму работников, оформлению кадровой документации и отчетности. Вам не нужно будет отслеживать изменения в законодательстве; нести расходы по зарплате сотрудников, содержанию рабочих мест; беспокоиться о своевременной сдаче в контрольные органы кадровых отчетов. Такая экономия особенно значима для небольших фирм и их предпринимателей.</p>
					</aside>

					<blockquote class="article-quote">
						<span class="article-quote__icon" aria-hidden="true"></span>
						<div class="article-quote__content">
							<p class="article-quote__title">Цитата</p>
							<p>«Юстиком» занимается аутсорсингом кадровых услуг более 18 лет. В нашей команде трудятся настоящие эксперты и фанаты своего дела. Мы предлагаем полный спектр услуг по найму работников, оформлению кадровой документации и отчетности. Вам не нужно будет отслеживать изменения в законодательстве; нести расходы по зарплате сотрудников, содержанию рабочих мест; беспокоиться о своевременной сдаче в контрольные органы кадровых отчетов. Такая экономия особенно значима для небольших фирм и их предпринимателей.</p>
						</div>
					</blockquote>

					<p>Владельцы бизнеса дорожат своим временем. Поэтому стремятся максимально оптимизировать рабочие процессы. Кадровый аутсорсинг стал эффективным инструментом построения процветающей компании. Эта услуга помогает профессионально и комплексно организовать HR-процессы, снизить нагрузку на штатных специалистов, выстраивать бесконфликтные трудовые отношения с персоналом.</p>

					<div class="content-notice content-notice--info">
						<span class="content-notice__icon" aria-hidden="true"></span>
						<p>Информационный блок с текстом</p>
					</div>

					<div class="content-lists">
						<div class="content-list-card">
							<h3 class="content-list-card__title">Маркированный список</h3>
							<ul class="content-list content-list--marked">
								<li>Пункт списка</li>
								<li>Пункт списка</li>
								<li>Пункт списка</li>
								<li>Пункт списка</li>
							</ul>
						</div>

						<div class="content-list-card">
							<h3 class="content-list-card__title">Нумерованный список</h3>
							<ol class="content-list content-list--numbered">
								<li>Пункт списка</li>
								<li>Пункт списка</li>
								<li>Пункт списка</li>
								<li>Пункт списка</li>
							</ol>
						</div>
					</div>

					<div class="content-notice content-notice--warning">
						<span class="content-notice__icon" aria-hidden="true"></span>
						<p>Предупреждающий блок с текстом</p>
					</div>
				</div>
			</div>
	</div>
</section>

linked-news

linked-services
