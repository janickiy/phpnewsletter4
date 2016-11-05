<?php

/********************************************
* PHP Newsletter 4.2.11
* Copyright (c) 2006-2016 Alexander Yanitsky
* Website: http://janicky.com
* E-mail: janickiy@mail.ru
* Skype: janickiy
********************************************/

// authorization
Auth::authorization();

$setting = $data->getSetting();
$update = new Update($setting['language']);
$newversion = $update->getVersion();
$currentversion = $PNSL["system"]["version"];

//require temlate class
require_once $PNSL["system"]["dir_root"].$PNSL["system"]["dir_libs"]."html_template/SeparateTemplate.php";
$tpl = SeparateTemplate::instance()->loadSourceFromFile($PNSL["system"]["template"]."update.tpl");

$tpl->assign('SCRIPT_VERSION', $PNSL["system"]["version"]);
$tpl->assign('STR_WARNING', $PNSL["lang"]["str"]["warning"]);
$tpl->assign('INFO_ALERT', $PNSL["lang"]["info"]["edit_user"]);
$tpl->assign('STR_ERROR', $PNSL["lang"]["str"]["error"]);
$tpl->assign('STR_LOGOUT', $PNSL["lang"]["str"]["logout"]);

$tpl->assign('TITLE_PAGE', $PNSL["lang"]["title_page"]["update"]);
$tpl->assign('TITLE', $PNSL["lang"]["title"]["update"]);

if($_POST["action"]){
	$_POST["license_key"] = trim($_POST["license_key"]);
	
	$result = $data->updateLicenseKey($_POST["license_key"]);

	if($result)
		$success = $PNSL["lang"]["msg"]["changes_added"];
	else
		$error = $PNSL["lang"]["error"]["web_apps_error"];
}
		
//alert
if($error) {
	$tpl->assign('ERROR_ALERT', $error);
}
	
if(!empty($success)){ 
	$tpl->assign('MSG_ALERT', $success);
}

if($update->checkNewVersion($PNSL["system"]["version"]) and $update->checkTree($currenversion)){
	if(Auth::checkLicenseKey()){
		$PNSL["lang"]["button"]["update"] = str_replace('%NEW_VERSION%', $newversion, $PNSL["lang"]["button"]["update"]);
		$PNSL["lang"]["button"]["update"] = str_replace('%SCRIPT_NAME%', $PNSL["lang"]["script"]["name"], $PNSL["lang"]["button"]["update"]);
		$tpl->assign('BUTTON_UPDATE', $PNSL["lang"]["button"]["update"]);
	}
	else{
		$tpl->assign('MSG_NO_UPDATES', $PNSL["lang"]["msg"]["update_not_available"]);
	}	
}
else{
	$PNSL["lang"]["msg"]["no_updates"] = str_replace('%SCRIPT_NAME%', $PNSL["lang"]["script"]["name"], $PNSL["lang"]["msg"]["no_updates"]);
	$PNSL["lang"]["msg"]["no_updates"] = str_replace('%NEW_VERSION%', $PNSL["system"]["version"], $PNSL["lang"]["msg"]["no_updates"]);
	$tpl->assign('MSG_NO_UPDATES', $PNSL["lang"]["msg"]["no_updates"]);
}

//menu
include_once "menu.php";

$tpl->assign('MAILING_STATUS', getCurrentMailingStatus());
$tpl->assign('STR_LAUNCHEDMAILING', $PNSL["lang"]["str"]["launchedmailing"]);
$tpl->assign('STR_STOPMAILING', $PNSL["lang"]["str"]["stopmailing"]);

//form
$tpl->assign('ACTION', $_SERVER['REQUEST_URI']);
$tpl->assign('STR_LICENSE_KEY', $PNSL["lang"]["str"]["license_key"]);
$tpl->assign('BUTTON_SAVE', $PNSL["lang"]["button"]["save"]);
$tpl->assign('STR_START_UPDATE', $PNSL["lang"]["str"]["start_update"]);

$tpl->assign('MSG_UPDATE_COMPLETED', $PNSL["lang"]["msg"]["update_completed"]);

//value
$tpl->assign('LICENSE_KEY', $data->getLicenseKey());

//footer
include_once "footer.php";

// display content
$tpl->display();