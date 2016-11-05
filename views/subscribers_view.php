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
$tpl = SeparateTemplate::instance()->loadSourceFromFile($PNSL["system"]["template"]."subscribers.tpl");

$tpl->assign('STR_WARNING', $PNSL["lang"]["str"]["warning"]);
$tpl->assign('SCRIPT_VERSION', $PNSL["system"]["version"]);
$tpl->assign('INFO_ALERT', $PNSL["lang"]["info"]["subscribers"]);
$tpl->assign('STR_ERROR', $PNSL["lang"]["str"]["error"]);

if($_POST['action']){
	
	if($_POST["action"] == 1){
		$result = $data->updateUsers('active');
		
		if($result)
			$success_alert = $PNSL["lang"]["msg"]["selected_users_activated"];
		else
			$error_alert = $PNSL["lang"]["error"]["web_apps_error"];
	}
	else if($_POST["action"] == 2){
		$result = $data->updateUsers('noactive');
		
		if($result)
			$success_alert = $PNSL["lang"]["msg"]["selected_users_deactivated"];
		else
			$error_alert = $PNSL["lang"]["error"]["web_apps_error"];
	}
	else if($_POST["action"] == 3){
		$result = $data->deleteUsers();
		
		if($result)
			$success_alert = $PNSL["lang"]["msg"]["selected_users_deleted"];
		else
			$error_alert = $PNSL["lang"]["error"]["web_apps_error"];
	}
}

if($_GET['remove'] == 'all'){
	$result = $data->removeAllUsers();    
	
	if($result)
		$success_alert = $PNSL["lang"]["msg"]["all_users_deleted"];
	else
		$error_alert = $PNSL["lang"]["error"]["web_apps_error"];	
}
else if($_GET['remove'] and preg_match("|^[\d]*$|",$_GET['remove'])) {
	$result = $data->removeUser();

	if($result)
		$success_alert = $PNSL["lang"]["msg"]["selected_users_deleted"];
	else
		$error_alert = $PNSL["lang"]["error"]["web_apps_error"];
}

$tpl->assign('TITLE_PAGE',$PNSL["lang"]["title_page"]["subscribers"]);
$tpl->assign('TITLE',$PNSL["lang"]["title"]["subscribers"]);

//menu
include_once "menu.php";

$tpl->assign('MAILING_STATUS', getCurrentMailingStatus());
$tpl->assign('STR_LAUNCHEDMAILING', $PNSL["lang"]["str"]["launchedmailing"]);
$tpl->assign('STR_STOPMAILING', $PNSL["lang"]["str"]["stopmailing"]);

$search = urldecode($_GET['search']);
$tpl->assign('SEARCH', $search);
$tpl->assign('ACTION', $_SERVER['REQUEST_URI']);

//alert
if($error_alert) {
	$tpl->assign('ERROR_ALERT', $error_alert);
}

if(!empty($success_alert)){ 
	$tpl->assign('MSG_ALERT', $success_alert);
}

//horizontal menu
$tpl->assign('STR_ADD_USER', $PNSL["lang"]["str"]["add_user"]);
$tpl->assign('STR_REMOVE_USER', $PNSL["lang"]["str"]["remove_user"]);
$tpl->assign('STR_IMPORT_USER', $PNSL["lang"]["str"]["import_user"]);
$tpl->assign('STR_EXPORT_USER', $PNSL["lang"]["str"]["export_user"]);
$tpl->assign('PROMPT_ADD_USER', $PNSL["lang"]["prompt"]["add_user"]);
$tpl->assign('PROMPT_REMOVE_SUBSCRIBERS', $PNSL["lang"]["prompt"]["remove_subscribers"]);
$tpl->assign('PROMPT_IMPORT_SUBSCRIBERS', $PNSL["lang"]["prompt"]["import_subscribers"]);
$tpl->assign('PROMPT_EXPORT_SUBSCRIBERS', $PNSL["lang"]["prompt"]["export_subscribers"]);

//form
$tpl->assign('FORM_SEARCH_NAME', $PNSL["lang"]["str"]["search_name"]);
$tpl->assign('BUTTON_FIND', $PNSL["lang"]["button"]["find"]);
$tpl->assign('ALERT_CLEAR_ALL', $PNSL["lang"]["alert"]["clear_all"]);
$tpl->assign('ALERT_SELECT_ACTION', $PNSL["lang"]["alert"]["select_action"]);
$tpl->assign('ALERT_CONFIRM_REMOVE', $PNSL["lang"]["alert"]["confirm_remove"]);

$tpl->assign('STR_REMOVE_ALL_SUBSCRIBERS', $PNSL["lang"]["str"]["remove_all_subscribers"]);
$tpl->assign('STR_CHECK_ALLBOX', $PNSL["lang"]["str"]["check_allbox"]);
$tpl->assign('TH_CLASS_NAME', $thclass["name"]);
$tpl->assign('TH_CLASS_EMAIL', $thclass["email"]);
$tpl->assign('TH_CLASS_TIME', $thclass["time"]);
$tpl->assign('TH_CLASS_STATUS', $thclass["status"]);

$order = array();
$order['name'] = "name";
$order['email'] = "email";
$order['time'] = "time";
$order['status'] = "status";

$strtmp = "name";

foreach($order as $parametr => $field)
{
	if(isset($_GET["".$parametr.""])){
		if($_GET["".$parametr.""] == "up"){
			$_GET["".$parametr.""] = "down";
			$strtmp = $field;
			$pl = "&".$field."=up";
			$thclass["$parametr"] = 'headerSortUp'; 
		}
		else{
			$_GET["".$parametr.""] = "up";
			$strtmp = "".$field." DESC";
			$pl = "&".$field."=down";
			$thclass["$parametr"] = 'headerSortDown'; 
		}
	}
	else {
		$_GET["".$parametr.""] = "up";
		$thclass["$parametr"] = 'headerUnSort'; 
	}
}

//pagination
if(isset($_COOKIE['pnumber_subscribers'])) 
	$pnumber = (int)$_COOKIE['pnumber_subscribers'];
else 
	$pnumber = 20;

$arr = array();
$arr = $data->getSubersArr($strtmp, $pnumber);

if(is_array($arr)){
	$return_backBlock = $tpl->fetch('show_return_back');	
	$return_backBlock->assign('STR_BACK', $PNSL["lang"]["str"]["return_back"]);
	
	if($_GET['search'] == ''){
		$number = $data->getTotal($pnumber);
		$page = $data->getPageNumber();

		if($page != 1) {
			$pervpage = '<a href="./?task=subscribers&page=1'.$pl.'">&lt;&lt;</a>';
			$perv = '<a href="./?task=subscribers&page='.($page - 1).''.$pl.'">&lt;</a>'; 
		}

		if($page != $number) {
			$nextpage = '<a href="./?task=subscribers&page='.($page + 1).''.$pl.'">&gt;</a>'; 
			$next = '<a href="./?task=subscribers&page='.$number.''.$pl.'">&gt;&gt;</a>';
		}									

		if($page - 2 > 0) $page2left = '<a href="./?task=subscribers&page='.($page - 2).''.$pl.'">...'.($page - 2).'</a>';
		if($page - 1 > 0) $page1left = '<a href="./?task=subscribers&page='.($page - 1).''.$pl.'">'.($page - 1).'</a>';
		if($page + 2 <= $number) $page2right = '<a href="./?task=subscribers&page='.($page + 2).''.$pl.'">'.($page + 2).'...</a>';
		if($page + 1 <= $number) $page1right = '<a href="./?task=subscribers&page='.($page + 1).''.$pl.'">'.($page + 1).'</a>';
	}
	else{
		$number = $data->getTotal();
		$page = $data->getPageNumber();

		if($page != 1) {
			$pervpage = '<a href="./?task=subscribers&search='.urlencode($_GET['search']).'&page=1'.$pl.'">&lt;&lt;</a>';
			$perv = '<a href="./?task=subscribers&search='.urlencode($_GET['search']).'&page='.($page - 1).''.$pl.'">&lt;</a>';
		}								

		if($page != $number) {
			$nextpage = '<a href="./?task=subscribers&search='.urlencode($_GET['search']).'&page='.($page + 1).''.$pl.'">&gt;</a>';
			$next = '<a href="./?task=subscribers&search='.urlencode($_GET['search']).'&page='.$number.''.$pl.'">&gt;&gt;</a>';
		}									

		if($page - 2 > 0) $page2left = '<a href="./?task=subscribers&search='.urlencode($_GET['search']).'&page='.($page - 2).''.$pl.'">...'.($page - 2).'</a>';
		if($page - 1 > 0) $page1left = '<a href="./?task=subscribers&search='.urlencode($_GET['search']).'&page='.($page - 1).''.$pl.'">'.($page - 1).'</a>';
		if($page + 2 <= $number) $page2right = '<a href=".?task=subscribers&search='.urlencode($_GET['search']).'&page='.($page + 2).''.$pl.'">'.($page + 2).'...</a>';
		if($page + 1 <= $number) $page1right = '<a href="./?task=subscribers&search='.urlencode($_GET['search']).'&page='.($page + 1).''.$pl.'">'.($page + 1).'</a>';
	}	
	
	if($page > 1) 
		$pagenav = "&page=".$page."";
	else 
		$pagenav = '';
		
	$rowBlock = $tpl->fetch('row');		
	
	if($search) $rowBlock->assign('SEARCH', $search);	
	
	//show table
	$rowBlock->assign('PAGENAV', $pagenav);
	$rowBlock->assign('GET_NAME', $_GET['name']);
	$rowBlock->assign('GET_EMAIL', $_GET['email']);
	$rowBlock->assign('GET_TIME', $_GET['time']);
	$rowBlock->assign('GET_STATUS', $_GET['status']);
	
	$rowBlock->assign('TH_CLASS_NAME', $thclass["name"]);	
	$rowBlock->assign('TH_CLASS_EMAIL', $thclass["email"]);	
	$rowBlock->assign('TH_CLASS_TIME', $thclass["time"]);	
	$rowBlock->assign('TH_CLASS_STATUS', $thclass["status"]);
	
	$rowBlock->assign('ALERT_CONFIRM_REMOVE', $PNSL["lang"]["alert"]["confirm_remove"]);
	$rowBlock->assign('ALERT_SELECT_ACTION', $PNSL["lang"]["alert"]["select_action"]);
	$rowBlock->assign('TABLE_NAME', $PNSL["lang"]["table"]["name"]);
	$rowBlock->assign('TABLE_EMAIL', $PNSL["lang"]["table"]["email"]);
	$rowBlock->assign('TABLE_ADDED', $PNSL["lang"]["table"]["added"]);
	$rowBlock->assign('TABLE_STATUS', $PNSL["lang"]["table"]["status"]);
	$rowBlock->assign('TABLE_ACTION', $PNSL["lang"]["table"]["action"]);
	
	for($i = 0; $i < count($arr); $i++){
		$columnBlock = $rowBlock->fetch('column');
		$str_stat = $arr[$i]['status'] == 'active' ? $PNSL["lang"]["str"]["activeuser"] : $PNSL["lang"]["str"]["noactive"];
		$tr_status_class = $arr[$i]['status'] == 'noactive' ? ' error"' : '';
		
		$columnBlock->assign('STATUS_CLASS',$tr_status_class);
		
		$columnBlock->assign('STR_CHECK_BOX', $PNSL["lang"]["str"]["check_box"]);
		$columnBlock->assign('ID_USER', $arr[$i]['id_user']);
		$columnBlock->assign('NAME', $arr[$i]['name']);
		$columnBlock->assign('EMAIL', $arr[$i]['email']);
		$columnBlock->assign('PUTDATE_FORMAT', $arr[$i]['putdate_format']);
		$columnBlock->assign('IP', $arr[$i]['ip']);
		$columnBlock->assign('GETHOSTBYADDR', $arr[$i]['ip']);
		$columnBlock->assign('PROMPT_IP_INFO', $PNSL["lang"]["prompt"]["ip_info"]);			
		
		$columnBlock->assign('STR_STAT', $str_stat);		
		$columnBlock->assign('STR_EDIT', $PNSL["lang"]["str"]["edit"]);		
		$columnBlock->assign('STR_REMOVE', $PNSL["lang"]["str"]["remove_user"]);
		$rowBlock->assign('column', $columnBlock);		
	}		
	
	$rowBlock->assign('FORM_CHOOSE_ACTION', $PNSL["lang"]["form"]["choose_action"]); 
	$rowBlock->assign('FORM_REMOVE', $PNSL["lang"]["form"]["remove"]);	
	$rowBlock->assign('FORM_ACTIVATE', $PNSL["lang"]["form"]["activate"]);	
	$rowBlock->assign('FORM_DEACTIVATE', $PNSL["lang"]["form"]["deactivate"]);	
	$rowBlock->assign('BUTTON_APPLY', $PNSL["lang"]["button"]["apply"]);		
	
	if($number>1){
		$paginationBlock = $rowBlock->fetch('pagination');
		$paginationBlock->assign('STR_PNUMBER',$PNSL["lang"]["str"]["pnumber"]);
		$paginationBlock->assign('CURRENT_PAGE', '<a>'.$page.'</a>');
		$paginationBlock->assign('STR_PAGES', $PNSL["lang"]["str"]["pages"]);
		$paginationBlock->assign('PAGE1RIGHT', $page1right);
		$paginationBlock->assign('PAGE2RIGHT', $page2right);

		$paginationBlock->assign('PAGE1LEFT', $page1left);
		$paginationBlock->assign('PAGE2LEFT', $page2left);

		$paginationBlock->assign('PERVPAGE', $pervpage);
		$paginationBlock->assign('NEXTPAGE', $nextpage);
	
		$paginationBlock->assign('PERV', $perv);
		$paginationBlock->assign('NEXT', $next);
		
		$paginationBlock->assign('PNUMBER',$pnumber);	
		$rowBlock->assign('pagination', $paginationBlock);	
	}
	
	$rowBlock->assign('STR_NUMBER_OF_SUBSCRIBERS', $PNSL["lang"]["str"]["number_of_subscribers"]);	
	$rowBlock->assign('NUMBER_OF_SUBSCRIBERS', $data->countSubscribers());	
	$tpl->assign('row', $rowBlock);		
}
else{
	if($_GET['search']) {
		$notfoundBlock = $tpl->fetch('notfound');
		$notfoundBlock->assign('MSG_NOTFOUND', $PNSL["lang"]["msg"]["notfound"]);
		$tpl->assign('notfound', $notfoundBlock);
	}
	else{
		$tpl->assign('EMPTY_LIST', $PNSL["lang"]["str"]["empty"]);
	}
}

//footer
include_once "footer.php";

//display content
$tpl->display();

?>