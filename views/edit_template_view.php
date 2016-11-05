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
$tpl = SeparateTemplate::instance()->loadSourceFromFile($PNSL["system"]["template"]."edit_template.tpl");

$settings = $data->getSetting();

$tpl->assign('SCRIPT_VERSION', $PNSL["system"]["version"]);
$tpl->assign('STR_WARNING', $PNSL["lang"]["str"]["warning"]);
$tpl->assign('INFO_ALERT', $PNSL["lang"]["info"]["edit_user"]);
$tpl->assign('STR_ERROR', $PNSL["lang"]["str"]["error"]);
$tpl->assign('STR_LOGOUT', $PNSL["lang"]["str"]["logout"]);

$tpl->assign('LANGUAGE', $settings['language']);

if($_POST["action"]){
	$error = array();
	$name = trim($_POST['name']);
	$body = trim($_POST['body']);
	
	if(empty($name)) $error[] = $PNSL["lang"]["error"]["empty_subject"];
	if(empty($body)) $error[] = $PNSL["lang"]["error"]["empty_content"];
	
	if(count($error) == 0){
		$fields = array();
		$fields['name'] = $name;
		$fields['body'] = $body;
		$fields['prior'] = $_POST['prior'];
		$fields['id_cat'] = $_POST['id_cat'];
	
		$result = $data->editTemplate($fields);
		
		if($result){
			header("Location: ./");
			exit();		
		}else {
			$alert_error = $PNSL["lang"]["error"]["web_apps_error"];
		}	
	}	
}

if($_GET['remove']){
	$result = $data->removeAttach($_GET['remove']);
	
	if($result){
		header("Location: ./?task=edit_template&id_template=".$_GET['id_template']);
		exit;	
	}
}

$tpl->assign('TITLE_PAGE', $PNSL["lang"]["title_page"]["edit_template"]);
$tpl->assign('TITLE', $PNSL["lang"]["title"]["edit_template"]);
$tpl->assign('INFO_ALERT', $PNSL["lang"]["info"]["edit_template"]);

//menu
include_once "menu.php";

$tpl->assign('MAILING_STATUS', getCurrentMailingStatus());
$tpl->assign('STR_LAUNCHEDMAILING', $PNSL["lang"]["str"]["launchedmailing"]);
$tpl->assign('STR_STOPMAILING', $PNSL["lang"]["str"]["stopmailing"]);

//alert
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

//form
$tpl->assign('ACTION', $_SERVER['REQUEST_URI']);
$tpl->assign('STR_FORM_SUBJECT', $PNSL["lang"]["form"]["subject"]);
$tpl->assign('STR_FORM_CONTENT', $PNSL["lang"]["form"]["content"]);
$tpl->assign('STR_FORM_NOTE', $PNSL["lang"]["form"]["supported_tags"]);
$tpl->assign('STR_SUPPORTED_TAGS_LIST', $PNSL["lang"]["str"]["supported_tags_list"]);
$tpl->assign('STR_FORM_ATTACH_FILE', $PNSL["lang"]["form"]["attach_file"]);
$tpl->assign('STR_FORM_CATEGORY_SUBSCRIBERS', $PNSL["lang"]["form"]["category_subscribers"]);
$tpl->assign('STR_FORM_PRIORITY_NORMAL', $PNSL["lang"]["form"]["priority_normal"]);
$tpl->assign('STR_FORM_PRIORITY_LOW', $PNSL["lang"]["form"]["priority_low"]);
$tpl->assign('STR_REMOVE', $PNSL["lang"]["str"]["remove"]);

$tpl->assign('STR_FORM_PRIORITY_HIGH', $PNSL["lang"]["form"]["priority_high"]);
$tpl->assign('FORM_PRIORITY', $PNSL["lang"]["form"]["priority"]);

$row = $data->getTemplate($_GET['id_template']);

//value
if(empty($_POST["name"]) and empty($_POST["id_template"])) $_POST["name"] = $row['name'];
if(empty($_POST['body']) and empty($_POST["id_template"])) $_POST['body'] = $row['body'];
if(empty($_POST['prior']) and empty($_POST["id_template"]))	$_POST['prior'] = $row['prior'];
if(empty($_POST['id_cat']) and empty($_POST["id_template"])) $_POST['id_cat'] = $row['id_cat'];

$tpl->assign('NAME', $_POST["name"]);
$tpl->assign('CONTENT', $_POST['body']);
$tpl->assign('ID_TEMPLATE', $_GET['id_template']);

$arr = $data->getAttachmentsList($_GET['id_template']);

if($arr){
	$attachBlock = $tpl->fetch('attach_list');
	$attachBlock->assign('STR_ATTACH_LIST', $PNSL["lang"]["str"]["str_attach_list"]);
	
	foreach($arr as $row)
	{
		$rowBlock = $attachBlock->fetch('row');		
		$rowBlock->assign('ATTACHMENT_FILE', $row['name']);
		$rowBlock->assign('ID_TEMPLATE', $_GET['id_template']);
		$rowBlock->assign('ID_ATTACHMENT', $row['id_attachment']);		
		$rowBlock->assign('STR_REMOVE', $PNSL["lang"]["str"]["remove"]);		
		$attachBlock->assign('row', $rowBlock);
	}
	
	$tpl->assign('attach_list', $attachBlock);	
}

if($_POST['prior'] == 1) 
	$tpl->assign('PRIOR1_CHECKED', $_POST['prior']);
else if($_POST['prior'] == 2) 
	$tpl->assign('PRIOR2_CHECKED', $_POST['prior']);
else 
	$tpl->assign('PRIOR3_CHECKED', $row['prior']);

$option = '';
$arr = $data->getCategoryOptionList();

if($arr){
	$selected = $_POST['id_cat'] == 0 ? ' selected="selected"' : "";
	$option .= "<option value=0".$selected.">".$PNSL["lang"]["form"]["sent_to_all"]."</option>";

	foreach($arr as $row){
		$selected = $_POST['id_cat'] == $row['id_cat'] ? ' selected="selected"' : "";
		$option .= "<option value=".$row['id_cat']."".$selected.">".$row['name']."</option>";
	}
}		

$tpl->assign('OPTION', $option);
$tpl->assign('BUTTON', $PNSL["lang"]["button"]["edit"]);

$tpl->assign('STR_SEND_TEST_EMAIL', $PNSL["lang"]["str"]["send_test_email"]);
$tpl->assign('BUTTON_SEND', $PNSL["lang"]["button"]["send"]);
$tpl->assign('STR_IDENTIFIED_FOLLOWING_ERRORS', $PNSL["lang"]["str"]["identified_following_errors"]);

//footer
include_once "footer.php";

// display content
$tpl->display();