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

// require temlate class
require_once $PNSL["system"]["dir_root"].$PNSL["system"]["dir_libs"]."html_template/SeparateTemplate.php";
$tpl = SeparateTemplate::instance()->loadSourceFromFile($PNSL["system"]["template"]."edit_user.tpl");

$tpl->assign('SCRIPT_VERSION', $PNSL["system"]["version"]);
$tpl->assign('STR_WARNING', $PNSL["lang"]["str"]["warning"]);
$tpl->assign('INFO_ALERT', $PNSL["lang"]["info"]["edit_user"]);
$tpl->assign('STR_ERROR', $PNSL["lang"]["str"]["error"]);
$tpl->assign('STR_LOGOUT', $PNSL["lang"]["str"]["logout"]);

if($_POST['action']){

	$error = array();

	$name = htmlspecialchars(trim($_POST['name']));
	$email = strtolower(trim($_POST['email']));
	
	if(empty($email)) $error[] = $PNSL["lang"]["error"]["empty_email"];
	if(!empty($email) and check_email($_POST['email'])) $error[] = $PNSL["lang"]["error"]["wrong_email"];
	
	if(count($error) == 0){
		$fields = array();
		$fields['name'] = $name;
		$fields['email'] = $email;	
	
		$result = $data->editUser($fields, $_POST['id_user'], $_POST['id_cat']);

		if($result){
			header("Location: ./?task=subscribers");
			exit;	
		}
		else {
			$alert_error = $PNSL["lang"]["error"]["edit_user"];
		}	
	}
}

$user = $data->getUserEdit($_GET['id_user']);

$tpl->assign('TITLE_PAGE', $PNSL["lang"]["title_page"]["edit_user"]);
$tpl->assign('TITLE', $PNSL["lang"]["title"]["edit_user"]);
$tpl->assign('INFO_ALERT', $PNSL["lang"]["info"]["edit_user"]);

//error alert
if(!empty($alert_error)) {
	$tpl->assign('ERROR_ALERT', $alert_error);
}

if(count($error) > 0){
	$errorBlock = $tpl->fetch('show_errors');
	$errorBlock->assign('STR_IDENTIFIED_FOLLOWING_ERRORS',$PNSL["lang"]["str"]["identified_following_errors"]);
			
	foreach($error as $row){
		$rowBlock = $errorBlock->fetch('row');
		$rowBlock->assign('ERROR', $row);
		$errorBlock->assign('row', $rowBlock);
	}
		
	$tpl->assign('show_errors', $errorBlock);
}

//menu
include_once "menu.php";

$tpl->assign('MAILING_STATUS', getCurrentMailingStatus());
$tpl->assign('STR_LAUNCHEDMAILING', $PNSL["lang"]["str"]["launchedmailing"]);
$tpl->assign('STR_STOPMAILING', $PNSL["lang"]["str"]["stopmailing"]);

$tpl->assign('RETURN_BACK', $PNSL["lang"]["str"]["return_back"]);
$tpl->assign('ACTION', $_SERVER['REQUEST_URI']);
$tpl->assign('FORM_NAME', $PNSL["lang"]["table"]["name"]);
$tpl->assign('FORM_EMAIL', $PNSL["lang"]["table"]["email"]);
$tpl->assign('FORM_CATEGORY', $PNSL["lang"]["table"]["category"]);
$tpl->assign('BUTTON', $PNSL["lang"]["button"]["edit"]);

$name = $_POST['name'] ? $_POST['name'] : $user['name'];
$email = $_POST['email'] ? $_POST['email'] : $user['email'];

$tpl->assign('NAME', $name);
$tpl->assign('EMAIL', $email);

$arr = $data->getGategoryList();

if($arr){
	foreach($arr as $row){
		$rowBlock = $tpl->fetch('row');
		$rowBlock->assign('ID_CAT', $row['id_cat']);
		$rowBlock->assign('CATEGORY_NAME', $row['name']);
		
		if($data->checkUserSub($row['id_cat'], $_GET['id_user'])>0){
			$rowBlock->assign('CHECKED', 'checked');
		}
		
		$tpl->assign('row', $rowBlock);	
	}
}

$tpl->assign('ID_USER', $user['id_user']);

//footer
include_once "footer.php";

// display content
$tpl->display();