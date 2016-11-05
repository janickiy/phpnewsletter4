<?php

/********************************************
* PHP Newsletter 4.1.3
* Copyright (c) 2006-2015 Alexander Yanitsky
* Website: http://janicky.com
* E-mail: janickiy@mail.ru
* Skype: janickiy
********************************************/

if($_POST['action']){
	$_POST['name'] = trim($_POST['name']);
	$_POST['email'] = trim($_POST['email']);
	
	if(empty($_POST['name'])) error($PNSL["lang"]["error"]["empty_your_name"]);
	if(empty($_POST['email'])) error($PNSL["lang"]["error"]["empty_email"]);
	if(check_email($_POST['email'])) error($PNSL["lang"]["error"]["wrong_email"]);
	if($data->checkExistEmail()) error($PNSL["lang"]["error"]["subscribe_is_already_done"]);
	
	$settings = $data->getSetting();
	
	$token = getRandomCode();
	$status = $settings['require_confirmation'] == 'yes' ? 'noactive' : 'active';
	
	$fields = array();
	$fields['id_user']   = 0;
	$fields['name']      = $_POST['name'];
	$fields['email']     = $_POST['email'];
	$fields['ip']        = getIP();
	$fields['token']     = $token;
	$fields['time']      = date("Y-m-d H:i:s");	
 	$fields['status']    = $status;
	$fields['time_send'] = '0000-00-00 00:00:00';	
		
	$insert_id = $data->makeSubscribe($fields);
	
	if($insert_id){
		$isert = $data->insertSubs($insert_id);
		$result = $data->sendNotification($insert_id, $token);

		echo '<!DOCTYPE html>';
		echo "<html>\n";
		echo "<head>\n";
		echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\n";
		echo "<title>".$PNSL["lang"]["subject"]["subscription"]."</title>\n";
		echo "</head>\n";
		echo "<body>\n";
	
		echo '<p style="text-align: center">';
	
		if($settings['require_confirmation'] == "yes")
			echo $PNSL["lang"]["msg"]["add_subscribe1"];
		else 
			echo $PNSL["lang"]["msg"]["add_subscribe2"];
		
		echo "<br><br><a href=http://".$_SERVER['SERVER_NAME'].">".$PNSL["lang"]["str"]["go_to_homepage"]."</a>\n";
		echo "</p>\n";
		echo "</body>\n";
		echo "</html>";
	
		exit;
	}
	else{
		error($PNSL["lang"]["error"]["subscribe"]);
	}	
}

//require temlate class
require_once $PNSL["system"]["dir_root"].$PNSL["system"]["dir_libs"]."html_template/SeparateTemplate.php";
$tpl = SeparateTemplate::instance()->loadSourceFromFile($PNSL["system"]["template"]."subform.tpl");

//form
$tpl->assign('TITLE_SUBSCRIBE', $PNSL["lang"]["title"]["subscribe"]);
$tpl->assign('ACTION', "http://".$_SERVER["SERVER_NAME"].root()."?task=subform");
$tpl->assign('STR_NAME', $PNSL["lang"]["table"]["name"]);
$tpl->assign('STR_EMAIL', $PNSL["lang"]["table"]["email"]);
$tpl->assign('BUTTON_SUBSCRIBE', $PNSL["lang"]["button"]["subscribe"]);

$arr = $data->getCategoryList();

foreach($arr as $row){
	$rowBlock = $tpl->fetch('row');
	$rowBlock->assign('ID_CAT', $row['id_cat']);
	$rowBlock->assign('NAME', $row['name']);
	$tpl->assign('row', $rowBlock);
}

// display content
$tpl->display();

?>