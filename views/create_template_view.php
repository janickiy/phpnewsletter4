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
$tpl = SeparateTemplate::instance()->loadSourceFromFile($PNSL["system"]["template"]."create_new_template.tpl");

$tpl->assign('SCRIPT_VERSION', $PNSL["system"]["version"]);
$tpl->assign('STR_WARNING', $PNSL["lang"]["str"]["warning"]);
$tpl->assign('INFO_ALERT', $PNSL["lang"]["info"]["create_template"]);
$tpl->assign('STR_ERROR', $PNSL["lang"]["str"]["error"]);

if($_POST["action"]){
	$error = array();

	$_POST['name'] = trim($_POST['name']);
	$_POST['body'] = trim($_POST['body']);
	
	if(empty($_POST['name'])) $error[] = $PNSL["lang"]["error"]["empty_subject"];
	if(empty($_POST['body'])) $error[] = $PNSL["lang"]["error"]["empty_content"];
	
	if(count($error) == 0){
		$fields = array();
		$fields['id_template'] = 0; 
		$fields['name'] = $_POST['name'];
		$fields['body'] = $_POST['body'];
		$fields['prior'] = $_POST['prior'];
		$fields['id_cat'] = $_POST['id_cat'];
		$fields['active'] = 'yes';
	
		$result = $data->addNewTemplate($fields);
		
		if($result){
			header("Location: ./");
			exit();
		}
		else{
			$alert_error = $PNSL["lang"]["error"]["web_apps_error"];
		}	
	}
}

$tpl->assign('TITLE_PAGE', $PNSL["lang"]["title_page"]["create_new_template"]);
$tpl->assign('TITLE', $PNSL["lang"]["title"]["create_new_template"]);

//$tpl->assign('NAMESCRIPT', $PNSL["lang"]["script"]["name"]);

// menu
include_once "menu.php";

$tpl->assign('MAILING_STATUS', getCurrentMailingStatus());
$tpl->assign('STR_LAUNCHEDMAILING', $PNSL["lang"]["str"]["launchedmailing"]);
$tpl->assign('STR_STOPMAILING', $PNSL["lang"]["str"]["stopmailing"]);

// alert
if($alert_error) {
	$tpl->assign('ERROR_ALERT', $alert_error);
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

// form
$tpl->assign('STR_FORM_SUBJECT', $PNSL["lang"]["form"]["subject"]);
$tpl->assign('STR_FORM_CONTENT', $PNSL["lang"]["form"]["content"]);
$tpl->assign('STR_FORM_NOTE', $PNSL["lang"]["form"]["supported_tags"]);
$tpl->assign('STR_REMOVE', $PNSL["lang"]["str"]["remove"]);
$tpl->assign('STR_SUPPORTED_TAGS_LIST', $PNSL["lang"]["str"]["supported_tags_list"]);
$tpl->assign('STR_FORM_ATTACH_FILE', $PNSL["lang"]["form"]["attach_file"]);
$tpl->assign('STR_FORM_CATEGORY_SUBSCRIBERS', $PNSL["lang"]["form"]["category_subscribers"]);
$tpl->assign('STR_FORM_PRIORITY_NORMAL', $PNSL["lang"]["form"]["priority_normal"]);
$tpl->assign('STR_FORM_PRIORITY_LOW', $PNSL["lang"]["form"]["priority_low"]);
$tpl->assign('STR_FORM_PRIORITY_HIGH', $PNSL["lang"]["form"]["priority_high"]);
$tpl->assign('FORM_PRIORITY', $PNSL["lang"]["form"]["priority"]);
$tpl->assign('BUTTON', $PNSL["lang"]["button"]["add"]);

// value
$tpl->assign('ACTION', $_SERVER['REQUEST_URI']);
$tpl->assign('NAME', $_POST['name']);
$tpl->assign('CONTENT', $_POST['body']);
$tpl->assign('ID_TEMPLATE', $_GET['id_template']);

if($_POST['prior'] == 1) 
	$tpl->assign('PRIOR1_CHECKED', 1);
else if($_POST['prior'] == 2) 
	$tpl->assign('PRIOR2_CHECKED', 2);
else 
	$tpl->assign('PRIOR3_CHECKED', 3);

$option = '';
$arr = $data->getCategoryOptionList();

if(is_array($arr)){
	$selected = ($_POST['id_cat'] == 0) || ($_POST['id_cat'] == '') ? ' selected="selected"' : "";
	$option .= "<option value=0".$selected.">".$PNSL["lang"]["form"]["sent_to_all"]."</option>";

	for($i = 0; $i < count($arr); $i++){
		$selected = $_POST['id_cat'] == $arr[$i]['id_cat'] ? ' selected="selected"' : "";
		$option .= "<option value=".$arr[$i]['id_cat']."".$selected.">".$arr[$i]['name']."</option>";
	}
}		

$tpl->assign('OPTION', $option);
$tpl->assign('STR_SEND_TEST_EMAIL', $PNSL["lang"]["str"]["send_test_email"]);
$tpl->assign('BUTTON_SEND', $PNSL["lang"]["button"]["send"]);

// footer
include_once "footer.php";

// display content
$tpl->display();

?>