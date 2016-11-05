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
$tpl = SeparateTemplate::instance()->loadSourceFromFile($PNSL["system"]["template"]."edit_category.tpl");

$tpl->assign('SCRIPT_VERSION', $PNSL["system"]["version"]);
$tpl->assign('STR_WARNING', $PNSL["lang"]["str"]["warning"]);
$tpl->assign('INFO_ALERT', $PNSL["lang"]["info"]["edit_category"]);
$tpl->assign('STR_ERROR', $PNSL["lang"]["str"]["error"]);

if($_POST["action"]){
	$_POST['name'] = htmlspecialchars(trim($_POST['name']));

	if(empty($_POST['name'])) $alert_error = $PNSL["lang"]["error"]["empty_category_name"];
	
	$fields = array();
	$fields['name'] = $_POST['name'];		
		
	if(empty($alert_error)){
		$result = $data->editCategoryRow($fields);
	
		if($result){
			header("Location: ./?task=category");
			exit;
		}
		else  {
			$alert_error = $PNSL["lang"]["error"]["edit_cat_name"];
		}	
	}
}

//title
$tpl->assign('TITLE_PAGE', $PNSL["lang"]["title_page"]["edit_category"]);
$tpl->assign('TITLE', $PNSL["lang"]["title"]["edit_category"]);

//$tpl->assign('NAMESCRIPT', $PNSL["lang"]["script"]["name"]);

//menu
include_once "menu.php";

$tpl->assign('MAILING_STATUS', getCurrentMailingStatus());
$tpl->assign('STR_LAUNCHEDMAILING', $PNSL["lang"]["str"]["launchedmailing"]);
$tpl->assign('STR_STOPMAILING', $PNSL["lang"]["str"]["stopmailing"]);

//error alert
if(!empty($alert_error)) {
	$tpl->assign('ERROR_ALERT', $alert_error);
}

//form
$tpl->assign('ACTION', $_SERVER['REQUEST_URI']);
$tpl->assign('STR_NAME', $PNSL["lang"]["table"]["title"]);
$tpl->assign('BUTTON', $PNSL["lang"]["button"]["edit"]);
$tpl->assign('STR_RETURN_BACK', $PNSL["lang"]["str"]["return_back"]);

$row = $data->getCategoryRow();

//value
$tpl->assign('NAME', $row['name']);
$tpl->assign('ID_CAT', $row['id_cat']);

//footer
include_once "footer.php";

// display content
$tpl->display();

?>