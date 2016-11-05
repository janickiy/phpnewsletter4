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
$tpl = SeparateTemplate::instance()->loadSourceFromFile($PNSL["system"]["template"]."add_category.tpl");

$tpl->assign('SCRIPT_VERSION', $PNSL["system"]["version"]);
$tpl->assign('STR_WARNING', $PNSL["lang"]["str"]["warning"]);
$tpl->assign('INFO_ALERT', $PNSL["lang"]["info"]["add_new_category"]);

if($_POST['action']){
	$_POST['name'] = trim(htmlspecialchars($_POST['name']));

	if(empty($_POST['name'])) $alert_error = $PNSL["lang"]["error"]["empty_category_name"];
	if(!empty($_POST['name']) and $data->checkExistCatName($_POST['name'])) $alert_error = $PNSL["lang"]["error"]["cat_name_exist"];
	
	if(!$alert_error){
		$fields = array();
		$fields['name'] = $_POST['name'];	
	
		$result = $data->addNewCategory($fields);
	
		if($result){
			header("Location: ./?task=category");
			exit();
		}
		else{ 
			$alert_error = $PNSL["lang"]["error"]["no_category_added"];
		}	
	}
}

$tpl->assign('TITLE_PAGE', $PNSL["lang"]["title_page"]["add_category"]);
$tpl->assign('TITLE', $PNSL["lang"]["title"]["add_category"]);

//$tpl->assign('NAMESCRIPT', $PNSL["lang"]["script"]["name"]);

//error alert
if(!empty($alert_error)) {
	$tpl->assign('STR_ERROR', $PNSL["lang"]["str"]["error"]);
	$tpl->assign('ERROR_ALERT', $alert_error);
}

//menu
include_once "menu.php";

$tpl->assign('MAILING_STATUS', getCurrentMailingStatus());
$tpl->assign('STR_LAUNCHEDMAILING', $PNSL["lang"]["str"]["launchedmailing"]);
$tpl->assign('STR_STOPMAILING', $PNSL["lang"]["str"]["stopmailing"]);

$tpl->assign('RETURN_BACK', $PNSL["lang"]["str"]["return_back"]);
$tpl->assign('ACTION', $_SERVER['REQUEST_URI']);
$tpl->assign('RETURN_BACK_LINK', './?task=category');
$tpl->assign('STR_NAME', $PNSL["lang"]["table"]["title"]);
$tpl->assign('NAME', $_POST['name']);
$tpl->assign('BUTTON', $PNSL["lang"]["button"]["add"]);

//footer
include_once "footer.php";

// display content
$tpl->display();

?>