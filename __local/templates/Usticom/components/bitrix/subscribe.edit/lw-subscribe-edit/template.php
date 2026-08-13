<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$APPLICATION->AddChainItem("Подписка на новости");
?>

<?$APPLICATION->IncludeComponent(
	"bitrix:breadcrumb", 
	"lw-breadcrumbs", 
	array(
		"PATH" => "",
		"SITE_ID" => "s1",
		"START_FROM" => "0",
		"COMPONENT_TEMPLATE" => "lw-breadcrumbs"
	),
	false
);?>

<section class="lw-section subscribe-section">
	<div class="lw-container-thin">
		<h1 class="lw-section-title">Подписка на новости</h1>
		<?if($arResult["ID"]<=0):?>
		<p class="lw-section-text">Получайте уведомления о новостях первыми !</p>
		<?endif;?>
	<?
		foreach($arResult["MESSAGE"] as $itemID=>$itemValue){
			echo ShowMessage(array("MESSAGE"=>$itemValue, "TYPE"=>"OK"));
			?>
			<script>
				dataLayer.push({'event': 'podpiska_novosti'});				
			</script>
			<?
		}			
		foreach($arResult["ERROR"] as $itemID=>$itemValue)
			echo ShowMessage(array("MESSAGE"=>$itemValue, "TYPE"=>"ERROR"));
		//whether to show the forms
		if($arResult["ID"] == 0 && empty($_REQUEST["action"]) || CSubscription::IsAuthorized($arResult["ID"])) {
			
			//status and unsubscription/activation section
			if($arResult["ID"]>0) {
				include("status.php");
			}

			//setting section
			if($arResult["ID"]<=0) {
				include("setting.php");
			}

			//show confirmation form
			if($arResult["ID"]>0 && $arResult["SUBSCRIPTION"]["CONFIRMED"] <> "Y") {
				include("confirmation.php");
			}
		} else {
			//subscription authorization form
			include("authorization_full.php");
		}
		?>
	</div>
</section>



