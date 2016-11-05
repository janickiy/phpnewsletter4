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
$tpl = SeparateTemplate::instance()->loadSourceFromFile($PNSL["system"]["template"]."import.tpl");

$tpl->assign('STR_WARNING', $PNSL["lang"]["str"]["warning"]);
$tpl->assign('SCRIPT_VERSION', $PNSL["system"]["version"]);
$tpl->assign('INFO_ALERT', $PNSL["lang"]["info"]["import"]);
$tpl->assign('STR_ERROR', $PNSL["lang"]["str"]["error"]);

$error = '';

if($_POST["action"]){

	if($_FILES['file']['tmp_name']){ 
		$ext = strrchr($_FILES["file"]["name"], ".");
	
		if($ext == '.xls' or $ext == '.xlsx'){
			$result = $data->importFromExcel();
		}
		else{	
			$result = $data->importFromText();		
		}
		
		if(!$result) $error = $PNSL["lang"]["error"]["no_import"];
	}
	else {
		$error = $PNSL["lang"]["error"]["no_import_file"];
	}
}

$tpl->assign('TITLE_PAGE', $PNSL["lang"]["title_page"]["import"]);
$tpl->assign('TITLE', $PNSL["lang"]["title"]["import"]);

//menu
include_once "menu.php";

$tpl->assign('MAILING_STATUS', getCurrentMailingStatus());
$tpl->assign('STR_LAUNCHEDMAILING', $PNSL["lang"]["str"]["launchedmailing"]);
$tpl->assign('STR_STOPMAILING', $PNSL["lang"]["str"]["stopmailing"]);

$temp = array();
$temp[] = 'iso-8859-1';
$temp[] = 'iso-8859-2';
$temp[] = 'iso-8859-3';
$temp[] = 'iso-8859-4';
$temp[] = 'iso-8859-5';
$temp[] = 'iso-8859-6';
$temp[] = 'iso-8859-7';
$temp[] = 'iso-8859-8';
$temp[] = 'iso-8859-9';
$temp[] = 'iso-8859-10';
$temp[] = 'iso-8859-13';
$temp[] = 'iso-8859-14';
$temp[] = 'iso-8859-15';
$temp[] = 'iso-8859-16';
$temp[] = 'koi8-r';
$temp[] = 'koi8-u';
$temp[] = 'windows-1250';
$temp[] = 'windows-1251';
$temp[] = 'windows-1252';
$temp[] = 'windows-1253';
$temp[] = 'windows-1254';
$temp[] = 'windows-1255';
$temp[] = 'windows-1256';
$temp[] = 'windows-1257';
$temp[] = 'windows-1258';
$temp[] = 'utf-8';

$charset = array();

foreach($temp as $row){
	$charset[$row] = charsetlist($row);
}

asort($charset);

$option = '';
foreach($charset as $key => $value){
	$option .= '<option value="'.$key.'">'.$value.'</option>';
}

$tpl->assign('STR_BACK', $PNSL["lang"]["str"]["return_back"]);

//alert
if(!empty($error)) {
	$tpl->assign('ERROR_ALERT', $error);
}

if(!empty($result)){ 
	$tpl->assign('MSG_ALERT', str_replace('%COUNT%', $result, $PNSL["lang"]["msg"]["imported_emails"]));
}

//form
$tpl->assign('ACTION', $_SERVER['REQUEST_URI']);
$tpl->assign('TABLE_DATABASE_FILE', $PNSL["lang"]["table"]["database_file"]);
$tpl->assign('BUTTON_ADD', $PNSL["lang"]["button"]["import"]);
$tpl->assign('TABLE_CATEGORY', $PNSL["lang"]["table"]["category"]);
$tpl->assign('OPTION', $option);
$tpl->assign('STR_CHARSET', $PNSL["lang"]["str"]["charset"]);
$tpl->assign('STR_NO', $PNSL["lang"]["str"]["no"]);

foreach ($data->getCategoryList() as $row){
	$rowBlock = $tpl->fetch('row');
	$rowBlock->assign('ID_CAT', $row['id']);
	$rowBlock->assign('NAME', $row['name']);
	$tpl->assign('row', $rowBlock);
}

//footer
include_once "footer.php";

// display content
$tpl->display();

?>