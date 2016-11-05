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

$subject = trim($_REQUEST['name']);
$body = trim($_REQUEST['body']);
$prior = $_REQUEST['prior'];
$email = trim($_REQUEST['email']);

$error = array();

if(empty($subject)) $error[] = $PNSL["lang"]["error"]["empty_subject"];
if(empty($body)) $error[] = $PNSL["lang"]["error"]["empty_content"];
if(empty($email)) $error[] = $PNSL["lang"]["error"]["empty_email"];
if(!empty($email) && check_email($email)) $error[] = $PNSL["lang"]["error"]["wrong_email"];

if(count($error) == 0){
	$result = $data->sendTestEmail($email, $subject, $body, $prior);

	if($result){
		$result_send = 'success';
		$msg = $PNSL["lang"]["msg"]["letter_was_sent"];
	}
	else{
		$result_send = 'error';
		$msg = $PNSL["lang"]["error"]["letter_wasnt_sent"];
	}
}
else{
	$result_send = 'errors';
	$msg = implode(",", $error);
}

header('Cache-Control: no-store, no-cache, must-revalidate');
header('Content-Type: application/xml; charset=utf-8');
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
echo "<document>\n";
echo "<result>".$result_send."</result>\n";
echo "<msg>".$msg."</msg>\n";
echo "</document>";