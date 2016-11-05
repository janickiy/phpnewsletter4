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

$subject = trim($_REQUEST['name']);
$body = trim($_REQUEST['body']);
$prior = $_REQUEST['prior'];
$email = trim($_REQUEST['email']);

$error = array();

if(empty($subject)) $error[] = $PNSL["lang"]["error"]["empty_subject"];
if(empty($body)) $error[] = $PNSL["lang"]["error"]["empty_content"];
if(empty($email)) $error[] = $PNSL["lang"]["error"]["empty_email"];

if(!empty($email) and check_email($email)) $error[] = $PNSL["lang"]["error"]["wrong_email"];

if(count($error) == 0){
	$result = $data->sendTestEmail($email, $subject, $body, $prior);

	if($result)
		show_success_alert($PNSL["lang"]["msg"]["letter_was_sent"]);
	else
		show_error_alert($PNSL["lang"]["error"]["letter_wasnt_sent"]);	
}
else{
	
	$error_msg = '<ul>';
	
	foreach($error as $row){
		$error_msg .= '<li>'.$row.'</li>';
	}
	
	$error_msg .= '</ul>';
	
	show_errors($error_msg);	
}

function show_success_alert($msg)
{
	global $PNSL;
	
	echo '<div class="alert alert-success">';
	echo '<button class="close" data-dismiss="alert">×</button>';
	echo $msg;
	echo '</div>';
}

function show_error_alert($msg)
{
	global $PNSL;

	echo '<div class="alert alert-error">';
	echo '<button class="close" data-dismiss="alert">×</button>';
	echo '<strong>'.$PNSL["lang"]["str"]["error"].'!</strong>';
	echo $msg;
	echo '</div>';
}

function show_errors($msg)
{
	global $PNSL;
	
	echo '<div class="alert alert-error">';
	echo '<a class="close" href="#" data-dismiss="alert">×</a>';
	echo '<h4 class="alert-heading">'.$PNSL["lang"]["str"]["identified_following_errors"].':</h4>';
	echo $msg;
	echo '</div>';
}

?>