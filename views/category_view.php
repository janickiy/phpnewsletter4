<?php

/********************************************
* PHP Newsletter 4.0.16
* Copyright (c) 2006-2015 Alexander Yanitsky
* Website: http://janicky.com
* E-mail: janickiy@mail.ru
* Skype: janickiy
********************************************/

// authorization
Auth::authorization();

// require temlate class
require_once $PNSL["system"]["dir_root"].$PNSL["system"]["dir_libs"]."html_template/SeparateTemplate.php";
$tpl = SeparateTemplate::instance()->loadSourceFromFile($PNSL["system"]["template"]."category.tpl");

$tpl->assign('SCRIPT_VERSION',$PNSL["system"]["version"]);
$tpl->assign('STR_WARNING',$PNSL["lang"]["str"]["warning"]);
$tpl->assign('INFO_ALERT',$PNSL["lang"]["info"]["category"]);

if($_GET['remove']){
	$result = $data->removeCategory($_GET['remove']);
	
	if($result)
		$success = $PNSL["lang"]["msg"]["category_removed"];
	else
		$error = $PNSL["lang"]["error"]["web_apps_error"];
}

$tpl->assign('TITLE_PAGE',$PNSL["lang"]["title_page"]["category"]);
$tpl->assign('TITLE',$PNSL["lang"]["title"]["category"]);

//alert
if($error) {
	$tpl->assign('STR_ERROR',$PNSL["lang"]["str"]["error"]);
	$tpl->assign('ERROR_ALERT',$error);
}
	
if(!empty($success)){ 
	$tpl->assign('MSG_ALERT',$success);
}

// menu
include_once "menu.php";

$tpl->assign('TH_TABLE_POSITION',$PNSL["lang"]["table"]["position"]);
$tpl->assign('TH_TABLE_NAME',$PNSL["lang"]["table"]["name"]);
$tpl->assign('TH_TABLE_NUMBER_SUBSCRIBERS',$PNSL["lang"]["table"]["number_subscribers"]);
$tpl->assign('TH_TABLE_ACTION',$PNSL["lang"]["table"]["action"]);

foreach ($data->getCategoryArr() as $row){
	$count = $data->getCountSubscription($row['id_cat']);
	$rowBlock = $tpl->fetch('row');
	$rowBlock->assign('NAME',$row['name']);
	$rowBlock->assign('COUNT',$count);
	$rowBlock->assign('STR_EDIT',$PNSL["lang"]["str"]["edit"]);
	$rowBlock->assign('ID_CAT',$row['id_cat']);
	
	$rowBlock->assign('STR_REMOVE',$PNSL["lang"]["str"]["remove"]);
	if($count > 0) $rowBlock->assign('ALERT_REMOVE_SUNBERS',$PNSL["lang"]["alert"]["remove_subers"]);
	
	$tpl->assign('row', $rowBlock);
}

$tpl->assign('BUTTON_ADD_CATEGORY',$PNSL["lang"]["button"]["add_category"]);

// footer
include_once "footer.php";

// display content
$tpl->display();

?>