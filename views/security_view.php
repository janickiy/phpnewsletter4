<?php

/********************************************
* PHP Newsletter 4.1.3
* Copyright (c) 2006-2015 Alexander Yanitsky
* Website: http://janicky.com
* E-mail: janickiy@mail.ru
* Skype: janickiy
********************************************/

// authorization
Auth::authorization();

// require temlate class
require_once $PNSL["system"]["dir_root"].$PNSL["system"]["dir_libs"]."html_template/SeparateTemplate.php";
$tpl = SeparateTemplate::instance()->loadSourceFromFile($PNSL["system"]["template"]."security.tpl");

$tpl->assign('STR_WARNING', $PNSL["lang"]["str"]["warning"]);
$tpl->assign('SCRIPT_VERSION', $PNSL["system"]["version"]);
$tpl->assign('INFO_ALERT', $PNSL["lang"]["info"]["security"]);
$tpl->assign('STR_ERROR', $PNSL["lang"]["str"]["error"]);

$error = array();
$action = "";
$action = $_POST["action"];

if($action){
	$_POST["current_password"] = trim($_POST["current_password"]);
	$_POST["password"] = trim($_POST["password"]);
	$_POST["password_again"] = trim($_POST["password_again"]);

	if(!$_POST["current_password"]){
		$action = "";
		$error[] = $PNSL["lang"]["error"]["enter_current_passwd"];
	}

	if(!$_POST["password"]){
		$action = "";
		$error[] = $PNSL["lang"]["error"]["password_isnt_entered"];
	}

	if(!$_POST["password_again"]){
		$action = "";
		$error[] = $PNSL["lang"]["error"]["re_enter_password"];
	}
	
	if($_POST["password"] and $_POST["password_again"] and $_POST["password"] != $_POST["password_again"]){
		$action = "";
		$error[] = $PNSL["lang"]["error"]["passwords_dont_match"];
	}
	
	if($_POST["current_password"]){
		$current_password = md5($_POST["current_password"]);
		
		if(Auth::getCurrentHash() != $current_password){
			$action = "";
			$error[] = $PNSL["lang"]["error"]["current_password_incorrect"];
		}
	}

	if(!$error) {
		$result = $data->changePassword();
		
		if($result){
			$action = "";
			$success = $PNSL["lang"]["msg"]["password_has_been_changed"];
		}	
		else{
			$action = "";
			$error_passw_change = $PNSL["lang"]["error"]["change_password"];
		}		
	}
}

if(empty($action)){
	$tpl->assign('TITLE_PAGE', $PNSL["lang"]["title_page"]["security"]);
	$tpl->assign('TITLE', $PNSL["lang"]["title"]["security"]);

	//$tpl->assign('NAMESCRIPT',$PNSL["lang"]["script"]["name"]);
	
	//menu
	include_once "menu.php";
	
	$tpl->assign('MAILING_STATUS', getCurrentMailingStatus());
	$tpl->assign('STR_LAUNCHEDMAILING', $PNSL["lang"]["str"]["launchedmailing"]);
	$tpl->assign('STR_STOPMAILING', $PNSL["lang"]["str"]["stopmailing"]);
	
	//alert
	if($error_passw_change) {
		$tpl->assign('ERROR_ALERT', $error_passw_change);
	}
	
	if(count($error) > 0){
		$errorBlock = $tpl->fetch('show_errors');
		$errorBlock->assign('STR_IDENTIFIED_FOLLOWING_ERRORS', $PNSL["lang"]["str"]["identified_following_errors"]);
			
		foreach($error as $row){
			$rowBlock = $errorBlock->fetch('row');
			$rowBlock->assign('ERROR', $row);
			$errorBlock->assign('row', $rowBlock);
		}
		
		$tpl->assign('show_errors', $errorBlock);
	}	

	if(!empty($success)){ 
		$tpl->assign('MSG_ALERT', $success);
	}

	//form
	$tpl->assign('ACTION', $_SERVER['REQUEST_URI']);
	$tpl->assign('STR_CURRENT_PASSWORD', $PNSL["lang"]["str"]["current_password"]);
	$tpl->assign('STR_PASSWORD', $PNSL["lang"]["str"]["password"]);
	$tpl->assign('STR_AGAIN_PASSWORD', $PNSL["lang"]["str"]["again_password"]);
	$tpl->assign('BUTTON_SAVE', $PNSL["lang"]["button"]["save"]);	

	//footer
	include_once "footer.php";

	$tpl->display();
}

?>