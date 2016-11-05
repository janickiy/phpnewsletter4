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

//include template
require_once $PNSL["system"]["dir_root"].$PNSL["system"]["dir_libs"]."html_template/SeparateTemplate.php";
$tpl = SeparateTemplate::instance()->loadSourceFromFile($PNSL["system"]["template"]."template.tpl");

$tpl->assign('SCRIPT_VERSION', $PNSL["system"]["version"]);
$tpl->assign('STR_WARNING', $PNSL["lang"]["str"]["warning"]);
$tpl->assign('INFO_ALERT', $PNSL["lang"]["info"]["edit_user"]);
$tpl->assign('STR_ERROR', $PNSL["lang"]["str"]["error"]);
$tpl->assign('STR_LOGOUT', $PNSL["lang"]["str"]["logout"]);

if($_POST["action"]){
	if($_POST["action"] == 2){
		$fields['active'] = 'yes';
		$result = $data->changeStatusNewsLetter($fields);
		
		if(!$result) $alert_error = $PNSL["lang"]["error"]["web_apps_error"];
	}
	else if($_POST["action"] == 3){
		$fields['active'] = 'no';
		$result = $data->changeStatusNewsLetter($fields);
		
		if(!$result) $alert_error = $PNSL["lang"]["error"]["web_apps_error"];
	}
	else if($_POST["action"] == 4){
		$result = $data->removeTemplate();
	}
	else{
		header("Location: ./");
		exit;		
	}
}

if($_GET['pos'] == 'up'){
	if($data->upPosition($_GET['id_template'])){
		header("Location: ./");
		exit;
	}
	else {
		$alert_error = $PNSL["lang"]["error"]["web_apps_error"];
	}	
}

if($_GET['pos'] == 'down'){
	if($data->downPosition($_GET['id_template'])){
		header("Location: ./");
		exit;
	}
	else{
		$alert_error = $PNSL["lang"]["error"]["web_apps_error"];
	}	
}

$tpl->assign('TITLE_PAGE', $PNSL["lang"]["title_page"]["template"]);
$tpl->assign('TITLE', $PNSL["lang"]["title"]["template"]);
$tpl->assign('INFO_ALERT', $PNSL["lang"]["info"]["template"]);

//menu
include_once "menu.php";

$tpl->assign('MAILING_STATUS', getCurrentMailingStatus());
$tpl->assign('STR_LAUNCHEDMAILING', $PNSL["lang"]["str"]["launchedmailing"]);
$tpl->assign('STR_STOPMAILING', $PNSL["lang"]["str"]["stopmailing"]);

//alert
if($alert_error) {
	$tpl->assign('ERROR_ALERT', $alert_error);
}

$tpl->assign('TH_TABLE_ACTIVITY', $PNSL["lang"]["table"]["activity"]);
$tpl->assign('TH_TABLE_CATEGORY', $PNSL["lang"]["table"]["category"]);
$tpl->assign('TH_TABLE_MAILER', $PNSL["lang"]["table"]["mailer"]);
$tpl->assign('TH_TABLE_POSITION', $PNSL["lang"]["table"]["position"]);
$tpl->assign('TH_TABLE_EDIT', $PNSL["lang"]["table"]["edit"]);
$tpl->assign('TH_TABLE_SEND', $PNSL["lang"]["table"]["send"]);

if(isset($_COOKIE['pnumber'])) 
	$pnumber = (int)$_COOKIE['pnumber'];
else 
	$pnumber = 5;

$arr = $data->getListArr($pnumber);

if($arr){

	//fetch row block from root template
	$rowBlock = $tpl->fetch('row');
	
	foreach($arr as $row){
	
		//fetch column block from row block
		$columnBlock = $rowBlock->fetch('column');
		
		if($row['id_cat'] == 0) { $row['catname'] = $PNSL["lang"]["str"]["general"]; }

		$active = $row['active'] == 'yes' ? $PNSL["lang"]["str"]["yes"] : $PNSL["lang"]["str"]["no"];
        
		$row['body'] = preg_replace('/<br(\s\/)?>/siU', "", $row['body']);
		$row['body'] = remove_html_tags($row['body']);
		$row['body'] = preg_replace('/\n/sU', "", $row['body']);
		$pos = strpos(substr($row['body'],500), " ");			

		if(strlen($row['body']) > 500) 
			$srttmpend = "...";
		else 
			$strtmpend = "";
			
		$class_noactive = $row['active'] == 'no' ? ' error' : '';	
			
		$columnBlock->assign('CLASS_NOACTIVE', $class_noactive);				
	
		$columnBlock->assign('ROW_ID_TEMPLATE', $row['id_template']);
		$columnBlock->assign('ROW_CONTENT', substr($row['body'], 0, 500 + $pos).$srttmpend);			
		$columnBlock->assign('STR_SEND', $PNSL["lang"]["str"]["send"]);	
		$columnBlock->assign('STR_UP', $PNSL["lang"]["str"]["up"]);	
		$columnBlock->assign('STR_DOWN', $PNSL["lang"]["str"]["down"]);
		$columnBlock->assign('ROW_POS', $row['pos']);
		$columnBlock->assign('ROW_CATNAME', $row['catname']);	
		$columnBlock->assign('ROW_TMPLNAME', $row['tmplname']);		
		$columnBlock->assign('ROW_ACTIVE', $active);		
		
		//assign modified column block back to row block
        $rowBlock->assign('column', $columnBlock);
	}	
	
	//assign modified row block back to root template
    $tpl->assign('row', $rowBlock);	
}

$tpl->assign('STR_CATEGORY', $PNSL["lang"]["table"]["category"]);	
$tpl->assign('ALERT_SELECT_ACTION', $PNSL["lang"]["alert"]["select_action"]);
$tpl->assign('ALERT_CONFIRM_REMOVE', $PNSL["lang"]["alert"]["confirm_remove"]);
$tpl->assign('ACTION', $_SERVER['REQUEST_URI']);
$tpl->assign('STR_PAUSE_SENDING', $PNSL["lang"]["str"]["pause_sending"]);
$tpl->assign('STR_STOP_SENDING', $PNSL["lang"]["str"]["stop_sending"]);
$tpl->assign('STR_REFRESH_SENDING', $PNSL["lang"]["str"]["refresh_sending"]);
$tpl->assign('ALERT_MALING_NOT_SELECTED', $PNSL["lang"]["alert"]["maling_not_selected"]);

$tpl->assign('STR_SENT', $PNSL["lang"]["str"]["sent"]);
$tpl->assign('STR_WASNT_SENT', $PNSL["lang"]["str"]["send_status_no"]);

//modal window
$tpl->assign('STR_TIME', $PNSL["lang"]["str"]["time"]);
$tpl->assign('STR_TIME_LEFT', $PNSL["lang"]["str"]["time_left"]);
$tpl->assign('STR_TIME_PASSED', $PNSL["lang"]["str"]["time_passed"]);
$tpl->assign('STR_SEND_TEST_EMAIL', $PNSL["lang"]["str"]["send_test_email"]);
$tpl->assign('STR_TOTAL', $PNSL["lang"]["str"]["total"]);
$tpl->assign('STR_GOOD', $PNSL["lang"]["str"]["good"]);
$tpl->assign('STR_BAD', $PNSL["lang"]["str"]["bad"]);
$tpl->assign('STR_SENDOUT_TO_SUBSCRIBERS', $PNSL["lang"]["str"]["sendout_to_subscribers"] );
$tpl->assign('STR_ONLINE_MAILINGLOG', $PNSL["lang"]["str"]["online_mailinglog"]);  
$tpl->assign('ALERT_ERROR_SERVER', $PNSL["lang"]["alert"]["error_server"]);

$tpl->assign('STR_ACTION', $PNSL["lang"]["str"]["action"]);
$tpl->assign('STR_ACTIVATE', $PNSL["lang"]["str"]["activate"]);
$tpl->assign('STR_SENDOUT', $PNSL["lang"]["str"]["sendout"]);
$tpl->assign('STR_DEACTIVATE', $PNSL["lang"]["str"]["deactivate"]);
$tpl->assign('STR_REMOVE', $PNSL["lang"]["str"]["remove"]);
$tpl->assign('STR_APPLY', $PNSL["lang"]["str"]["apply"]);

//pagination
$number = $data->getTotal($pnumber);
$page = $data->getPageNumber();

if($page != 1) {
	$pervpage = '<a href="./?page=1">&lt;&lt;</a>';
	$perv = '<a href="./?page='.($page - 1).'">&lt;</a>';
}

if($page != $number) {
	$nextpage = '<a href="./?page='.($page + 1).'">&gt;</a>';
	$next = '<a href="./?page='.$number.'">&gt;&gt;</a>';
}

if($page - 2 > 0) $page2left = '<a href="./?page='.($page - 2).'">...'.($page - 2).'</a>';
if($page - 1 > 0) $page1left = '<a href="./?page='.($page - 1).'">'.($page - 1).'</a>';
if($page + 2 <= $number) $page2right = '<a href="./?page='.($page + 2).'">'.($page + 2).'...</a>';
if($page + 1 <= $number) $page1right = '<a href="./?page='.($page + 1).'">'.($page + 1).'</a>';

if($number > 1){
	$paginationBlock = $tpl->fetch('pagination');
	$paginationBlock->assign('STR_PNUMBER', $PNSL["lang"]["str"]["pnumber"]);
	$paginationBlock->assign('CURRENT_PAGE', '<a>'.$page.'</a>');
	$paginationBlock->assign('PAGE1RIGHT', $page1right);
	$paginationBlock->assign('PAGE2RIGHT', $page2right);

	$paginationBlock->assign('PAGE1LEFT', $page1left);
	$paginationBlock->assign('PAGE2LEFT', $page2left);

	$paginationBlock->assign('PERVPAGE', $pervpage);
	$paginationBlock->assign('PERV', $perv);

	$paginationBlock->assign('NEXTPAGE', $nextpage);
	$paginationBlock->assign('NEXT', $next);
	
	$paginationBlock->assign('PNUMBER', $pnumber);
	$tpl->assign('pagination', $paginationBlock);	
}

//footer
include_once "footer.php";
		
//display content
$tpl->display();