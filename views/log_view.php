<?php

/********************************************
* PHP Newsletter 4.0.16
* Copyright (c) 2006-2015 Alexander Yanitsky
* Website: http://janicky.com
* E-mail: janickiy@mail.ru
* Skype: janickiy
********************************************/

// authorization
Auth::authorization();

// require temlate class
require_once $PNSL["system"]["dir_root"].$PNSL["system"]["dir_libs"]."html_template/SeparateTemplate.php";
$tpl = SeparateTemplate::instance()->loadSourceFromFile($PNSL["system"]["template"]."log.tpl");

$tpl->assign('STR_WARNING',$PNSL["lang"]["str"]["warning"]);
$tpl->assign('SCRIPT_VERSION',$PNSL["system"]["version"]);
$tpl->assign('INFO_ALERT',$PNSL["lang"]["info"]["log"]);

if(isset($_GET['clear_log'])){
	$result = $data->clearLog();
	
	if($result)
		$alert_success = $PNSL["lang"]["msg"]["clear_log"];
	else
		$alert_error = $PNSL["lang"]["error"]["clear_log"];				
}

$order = array();
$order['name'] = "s.name";
$order['email'] = "email";
$order['time'] = "a.time";
$order['success'] = "success";
$order['readmail'] = "readmail";
$order['catname'] = "c.name";
	
$strtmp = "id_log";

foreach($order as $parametr => $field){
	if(isset($_GET["".$parametr.""])){
		if($_GET["".$parametr.""] == "up"){
			$_GET["".$parametr.""] = "down";
			$strtmp = $field;
			$thclass["$parametr"] = 'headerSortDown'; 
		}
		else{
			$_GET["".$parametr.""] = "up";
			$strtmp = "".$field." DESC";
			$thclass["$parametr"] = 'headerSortUp'; 
		}
	}
	else {
		$_GET["".$parametr.""] = "up";
		$thclass["$parametr"] = 'headerUnSort'; 
	}
}

$tpl->assign('TITLE_PAGE',$PNSL["lang"]["title_page"]["log"]);
$tpl->assign('TITLE',$PNSL["lang"]["title"]["log"]);


//menu
include_once "menu.php";

if($_GET['id_log']){
	$blockDetailLog = $tpl->fetch('DetailLog');
	$blockDetailLog->assign('STR_BACK',$PNSL["lang"]["str"]["return_back"]);
	$blockDetailLog->assign('TH_TABLE_MAILER',$PNSL["lang"]["table"]["mailer"]);
	$blockDetailLog->assign('TH_TABLE_CATNAME',$PNSL["lang"]["table"]["category"]);
	$blockDetailLog->assign('TH_TABLE_TIME',$PNSL["lang"]["table"]["time"]);
	$blockDetailLog->assign('TH_TABLE_STATUS',$PNSL["lang"]["table"]["status"]);
	$blockDetailLog->assign('TH_TABLE_READ',$PNSL["lang"]["table"]["read"]);
	$blockDetailLog->assign('TH_TABLE_ERROR',$PNSL["lang"]["table"]["error"]);
	
	$blockDetailLog->assign('THCLASS_NAME', $thclass["name"]);
	$blockDetailLog->assign('THCLASS_EMAIL', $thclass["email"]);
	$blockDetailLog->assign('THCLASS_CATNAME',$thclass["catname"]);
	$blockDetailLog->assign('THCLASS_TIME',$thclass["time"]);
	$blockDetailLog->assign('THCLASS_SUCCESS',$thclass["success"]);
	$blockDetailLog->assign('THCLASS_READMAIL',$thclass["readmail"]);
	
	$arr = $data->getDetaillog($strtmp);

	if(is_array($arr)){
		$blockDetailLog->assign('ID_LOG',$_GET['id_log']);
		$blockDetailLog->assign('GET_NAME',$_GET['name']);
		$blockDetailLog->assign('GET_EMAIL',$_GET['email']);
		$blockDetailLog->assign('GET_CATNAME',$_GET['catname']);
		$blockDetailLog->assign('GET_TIME',$_GET['time']);
		$blockDetailLog->assign('GET_SUCCESS',$_GET['success']);
		$blockDetailLog->assign('GET_READMAIL',$_GET['readmail']);				

		foreach($arr as $row){
			$status = $row['success'] == 'yes' ? $PNSL["lang"]["str"]["send_status_yes"] : $PNSL["lang"]["str"]["send_status_no"];  
			$read = $row['readmail'] == 'yes' ? $PNSL["lang"]["str"]["yes"] : $PNSL["lang"]["str"]["no"]; 
			$catname = $row['id_cat'] == 0 ? $PNSL["lang"]["str"]["general"] : $row['catname'];		
		
			$rowBlock = $blockDetailLog->fetch('row');
			$rowBlock->assign('NAME',$row['name']);
			$rowBlock->assign('EMAIL',$row['email']);
			$rowBlock->assign('CATNAME',$catname);
			$rowBlock->assign('TIME',$row['time']);
			$rowBlock->assign('STATUS',$status);
			$rowBlock->assign('READ',$read);
			$rowBlock->assign('ERRORMSG',$row['errormsg']);
			
			$blockDetailLog->assign('row', $rowBlock);
		}	
	}
	
	$tpl->assign('DetailLog', $blockDetailLog);
}
else
{
	$blockLogList = $tpl->fetch('LogList');	
	
	//alert error
	if(!empty($alert_error)) {
		$blockLogList->assign('STR_ERROR',$PNSL["lang"]["str"]["error"]);
		$blockLogList->assign('ERROR_ALERT',$alert_error);
	}
	
	//alert success
	if(!empty($alert_success)){ 
		$blockLogList->assign('MSG_ALERT',$alert_success);
	}
	
	$blockLogList->assign('STR_CLEAR_LOG',$PNSL["lang"]["str"]["clear_log"]);
	$blockLogList->assign('TH_TABLE_TIME',$PNSL["lang"]["table"]["time"]);
	
	$blockLogList->assign('TH_TABLE_TOTAL',$PNSL["lang"]["table"]["total"]);
	$blockLogList->assign('TH_TABLE_SENT',$PNSL["lang"]["table"]["sent"]);
	$blockLogList->assign('TH_TABLE_NOSENT',$PNSL["lang"]["table"]["nosent"]);
	
	$blockLogList->assign('TH_TABLE_READ',$PNSL["lang"]["table"]["read"]);
	$blockLogList->assign('TH_TABLE_DOWNLOAD_REPORT',$PNSL["lang"]["table"]["download_report"]);

	$arr = $data->getLogArr();

	if(is_array($arr)){	
		for($i=0; $i<count($arr); $i++){
			$rowBlock = $blockLogList->fetch('row');
			$rowBlock->assign('TIME', $arr[$i]['time']);
			$rowBlock->assign('ID_LOG', $arr[$i]['id_log']);
			$total = $data->countLetters($arr[$i]['id_log']);
			$total_sent = $data->countSent($arr[$i]['id_log']);
			$total_nosent = $total - $total_sent;
			$rowBlock->assign('TOTAL',$total);
			$rowBlock->assign('TOTAL_SENT',$total_sent);
			$rowBlock->assign('TOTAL_NOSENT',$total_nosent);
			$total_read = $data->countRead($arr[$i]['id_log']);
			$rowBlock->assign('TOTAL_READ',$total_read);
			$rowBlock->assign('STR_DOWNLOAD',$PNSL["lang"]["str"]["download"]);
			
			$blockLogList->assign('row', $rowBlock);
		}
	}

	$number = $data->getTotal();
	$page = $data->getPageNumber();

	if($page != 1) {
		$pervpage = '<a href="./?task=log&page=1">&lt;&lt;</a>';
		$perv = '<a href="./?task=log&page'.($page - 1).'">&lt;</a>';
	}							

	if($page != $number) {
		$nextpage = '<a href="./?task=log&page='.($page + 1).'">&gt;</a>';
		$next = '<a href="./?task=log&page='.$number.'">&gt;&gt;</a>';
	}									

	if($page - 2 > 0) $page2left = '<a href="./?task=log&page='.($page - 2).'">...'.($page - 2).'</a>';
	if($page - 1 > 0) $page1left = '<a href="./?task=log&page='.($page - 1).'">'.($page - 1).'</a>';
	if($page + 2 <= $number) $page2right = '<a href="./?task=log&page='.($page + 2).'">'.($page + 2).'...</a>';
	if($page + 1 <= $number) $page1right = '<a href="./?task=log&page='.($page + 1).'">'.($page + 1).'</a>';

	if($number>1){
		$paginationBlock = $blockLogList->fetch('pagination');	
	
		$paginationBlock->assign('CURRENT_PAGE','<a>'.$page.'</a>');
		$paginationBlock->assign('PAGE1RIGHT', $page1right);
		$paginationBlock->assign('PAGE2RIGHT', $page2right);
		$paginationBlock->assign('PAGE1LEFT', $page1left);
		$paginationBlock->assign('PAGE2LEFT', $page2left);
		$paginationBlock->assign('PERVPAGE', $pervpage);
		$paginationBlock->assign('NEXTPAGE', $nextpage);
		$paginationBlock->assign('PERV', $perv);
		$paginationBlock->assign('NEXT', $next);
		
		$blockLogList->assign('pagination', $paginationBlock);			
	}
	
	$tpl->assign('LogList', $blockLogList);
}

//footer
include_once "footer.php";

// display content
$tpl->display();

?>