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
$tpl = SeparateTemplate::instance()->loadSourceFromFile($PNSL["system"]["template"]."whois.tpl");

$tpl->assign('SCRIPT_VERSION', $PNSL["system"]["version"]);
$tpl->assign('STR_WARNING', $PNSL["lang"]["str"]["warning"]);
$tpl->assign('INFO_ALERT', $PNSL["lang"]["info"]["whois"]);
$tpl->assign('STR_ERROR', $PNSL["lang"]["str"]["error"]);

$tpl->assign('TITLE_PAGE', $PNSL["lang"]["title_page"]["whois"]);
$tpl->assign('TITLE', $PNSL["lang"]["title"]["whois"]);

$tpl->assign('RETURN_BACK', $PNSL["lang"]["str"]["return_back"]);

//menu
include_once "menu.php";

$tpl->assign('MAILING_STATUS', getCurrentMailingStatus());
$tpl->assign('STR_LAUNCHEDMAILING', $PNSL["lang"]["str"]["launchedmailing"]);
$tpl->assign('STR_STOPMAILING', $PNSL["lang"]["str"]["stopmailing"]);

$error = '';

if($_GET['ip']){
	$sock = @fsockopen("whois.ripe.net",43,$errno,$errstr);
	
	if(!$sock){
		$error = $errno($errstr);
	}
	else{
		$whoisBlock = $tpl->fetch('whois');
		
		$whoisBlock->assign('TH_TABLE_IP_INFO', $PNSL["lang"]["table"]["ip_info"]);
		
		fputs ($sock, $_GET['ip']."\r\n");
		
		while (!feof($sock)){
			$rowBlock = $whoisBlock->fetch('row');
			$rowBlock->assign('SOCK', str_replace(":",":&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" ,fgets ($sock,128)));
			$whoisBlock->assign('row', $rowBlock);
		}
	
		$tpl->assign('whois', $whoisBlock);
	}	
}
else{
	$error = $PNSL["lang"]["error"]["service_unavailable"];
}

$tpl->assign('STR_ERROR', $error);

//footer
include_once "footer.php";

// display content
$tpl->display();

?>