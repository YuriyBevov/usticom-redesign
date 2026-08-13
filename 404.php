<?
	include_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/urlrewrite.php');

	CHTTP::SetStatus("404 Not Found");
	@define("ERROR_404","Y");

	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

	$APPLICATION->SetPageProperty("not_show_nav_chain", "Y");
	$APPLICATION->SetTitle("Страница не найдена");
?>

<div class="lw-container-thin ">
	<div class="section-404">
		<span class="section-404__title">404</span>
		<span class="section-404__text lw-section-title">Такой страницы не существует...</span>
		<img src="<?=SITE_TEMPLATE_PATH?>/assets/img/404.png" alt="404" width="640" height="400">

		<a href="/" class="lw-main-btn">Вернуться на главную</a>
	</div>
</div>

<style>
	.section-404 {
		display: flex;
		flex-direction: column;
		align-items: center;
		justify-content: center;
		text-align: center;
		padding: 30px 0;
		margin-top: 20px;
	}

	.section-404__title {
		display: block;
		font-size: 120px;
		font-weight: 600;
		color: var(--accent);
	}

	.section-404__text {
		margin-bottom: 10px;
	}

	@media(max-width: 534px) {
		.section-404__title {
			font-size: 80px;
		}
	}

	.section-404 a {
		margin: 40px 0;
		font-weight: 600;
		min-height: 48px;
		color: var(--white);
    	background-color: var(--accent);
		transition: opacity var(--main-transition);
	}

	.section-404 a:hover {
		opacity: 0.6;
	}
</style>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>