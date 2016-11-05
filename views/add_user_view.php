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
$tpl = SeparateTemplate::instance()->loadSourceFromFile($PNSL["system"]["template"]."add_user.tpl");

$tpl->assign('SCRIPT_VERSION', $PNSL["system"]["version"]);
$tpl->assign('STR_WARNING', $PNSL["lang"]["str"]["warning"]);
$tpl->assign('INFO_ALERT', $PNSL["lang"]["info"]["add_user"]);

if($_POST['action']){
	$error = array();

	$_POST['name'] = trim($_POST['name']);
	$_POST['email'] = trim($_POST['email']);
	
	if(empty($_POST['email'])) $error[] = $PNSL["lang"]["error"]["empty_email"];
	
	if(!empty($_POST['email']) and check_email($_POST['email'])){
		$error[] = $PNSL["lang"]["error"]["wrong_email"];
	}
	
	if(!empty($_POST['email']) and $data->checkExistEmail($_POST['email'])){
		$error[] = $PNSL["lang"]["error"]["subscribe_is_already_done"];
	}
	
	if(count($error) == 0){
		$fields = array();
		$fields['id_user']   = 0;
		$fields['name']      = $_POST['name'];
		$fields['email']     = $_POST['email'];
		$fields['ip']        = '';
		$fields['token']     = getRandomCode();
		$fields['time']      = date("Y-m-d H:i:s");	
		$fields['status']    = 'active';
		$fields['time_send'] = '0000-00-00 00:00:00';
	
		$result = $data->addUser($fields);
		
		if($result){
			header("Location: ./?task=subscribers");
			exit;
		}
	}
}

$tpl->assign('TITLE_PAGE', $PNSL["lang"]["title_page"]["add_user"]);
$tpl->assign('TITLE', $PNSL["lang"]["title"]["add_user"]);

//$tpl->assign('NAMESCRIPT',$PNSL["lang"]["script"]["name"]);

// menu
include_once "menu.php";

$tpl->assign('MAILING_STATUS', getCurrentMailingStatus());
$tpl->assign('STR_LAUNCHEDMAILING', $PNSL["lang"]["str"]["launchedmailing"]);
$tpl->assign('STR_STOPMAILING', $PNSL["lang"]["str"]["stopmailing"]);

//alert
if(!empty($success)){ 
	$tpl->assign('MSG_ALERT',$success);
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

//form
$tpl->assign('RETURN_BACK', $PNSL["lang"]["str"]["return_back"]);
$tpl->assign('ACTION', $_SERVER['REQUEST_URI']);
$tpl->assign('FORM_NAME', $PNSL["lang"]["table"]["name"]);
$tpl->assign('FORM_EMAIL', $PNSL["lang"]["table"]["email"]);
$tpl->assign('FORM_CATEGORY', $PNSL["lang"]["table"]["category"]);
$tpl->assign('BUTTON', $PNSL["lang"]["button"]["add"]);

//value
$tpl->assign('NAME', $_POST['name']);
$tpl->assign('EMAIL', $_POST['email']);

$arr = $data->getGategoryList();

if(is_array($arr)){
	for($i = 0; $i < count($arr); $i++){
		$rowBlock = $tpl->fetch('row');
		$rowBlock->assign('ID_CAT', $arr[$i]['id_cat']);
		$rowBlock->assign('CATEGORY_NAME', $arr[$i]['name']);
		
		if($data->checkSub($arr[$i]['id_cat'])) $rowBlock->assign('CHECKED','checked');
	
		$tpl->assign('row', $rowBlock);	
	}
}

//footer
include_once "footer.php";

// display content
$tpl->display();

?>