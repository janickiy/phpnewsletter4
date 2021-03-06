<?php

/********************************************
* PHP Newsletter 4.0.16
* Copyright (c) 2006-2015 Alexander Yanitsky
* Website: http://janicky.com
* E-mail: janickiy@mail.ru
* Skype: janickiy
********************************************/

Error_Reporting(0);

set_time_limit(0);

require_once "config/config.php";
require_once "engine/libraries/functions.php";
require_once "libraries/PHPMailer/class.phpmailer.php";

$dbh = new mysqli($PNSL["config"]["db"]["host"],$PNSL["config"]["db"]["user"],$PNSL["config"]["db"]["passwd"],$PNSL["config"]["db"]["name"]);

if(mysqli_connect_errno()){
	exit("Error connecting to MySQL database: Database server ".$PNSL["config"]["db"]["host"]." is not available!");
}

if($PNSL["config"]["db"]["charset"] != '') { 
	$dbh->query("SET NAMES ".$PNSL["config"]["db"]["charset"]."");
}

$fh = fopen(__FILE__, 'r');

if(!flock($fh, LOCK_EX | LOCK_NB)){
	exit('Script is already running');
}

$mailcountno = 0;
$mailcount = 0;

$query = "SELECT * FROM ".$PNSL["config"]["db"]["prefix"]."settings";
$result = $dbh->query($query);

if(!$result) exit('Error executing SQL query!');

$settings = $result->fetch_array();
$result->close();

$update = "UPDATE ".$PNSL["config"]["db"]["prefix"]."process SET process='start'";

if(!$dbh->query($update)) exit('Error executing SQL query!');

$insert = "INSERT INTO ".$PNSL["config"]["db"]["prefix"]."log (`id_log`,`time`) VALUES (0,now())";
$result = $dbh->prepare($insert);
	
if(!$result) exit('Error executing SQL query!');
$result->execute();	
$id_log = $result->insert_id;

$query = "SELECT * FROM ".$PNSL["config"]["db"]["prefix"]."charset WHERE id_charset=".$settings['id_charset'];
$result = $dbh->query($query);

if(!$result) exit('Error executing SQL query!');

$char = $result->fetch_array();
$charset = $char['charset'];

$result->close();

if($charset != 'utf-8') {
	$from = iconv('utf-8',$charset,$from);
	if(!empty($settings['organization'])) $settings['organization'] = iconv('utf-8',$charset,$settings['organization']);
}	
		
$from = $settings['email_name'] == '' ? $_SERVER["SERVER_NAME"] : $settings['email_name'];

$query = "SELECT * FROM ".$PNSL["config"]["db"]["prefix"]."template WHERE active='yes' ORDER by pos";
$result_send = $dbh->query($query);

if(!$result_send) exit('Error executing SQL query!');

if($result_send->num_rows>0){
	$m = new PHPMailer();	

	if($settings['add_dkim'] == 'yes' and file_exists($settings['dkim_private'])){
		$m->DKIM_domain = $settings['dkim_domain'];
		$m->DKIM_private = $settings['dkim_private'];
		$m->DKIM_selector = $settings['dkim_selector'];
		$m->DKIM_passphrase = $settings['dkim_passphrase'];
		$m->DKIM_identity = $settings['dkim_identity'];		
	}
		
	if($settings['how_to_send'] == 2){
		$m->IsSMTP();
			
		$m->SMTPAuth = true;
		$m->SMTPKeepAlive = true;
		$m->Host = $settings['smtp_host'];
		$m->Port = $settings['smtp_port'];
		$m->Username = $settings['smtp_username'];
		$m->Password = $settings['smtp_password'];
			
		if($settings['smtp_secure'] == 'ssl')
			$m->SMTPSecure  = 'ssl';
		else if($settings['smtp_secure'] == 'tls')
			$m->SMTPSecure  = 'tls';
				
		if($settings['smtp_aut'] == 'plain')	
			$m->AuthType = 'PLAIN';
		else if($settings['smtp_aut'] == 'cram-md5')
			$m->AuthType = 'CRAM-MD5';
		
		$m->Timeout = $settings['smtp_timeout'];
	}
	else if($settings['how_to_send'] == 3 and !empty($settings['sendmail'])){
		$m->IsSendmail();
		$m->Sendmail = $settings['sendmail'];
	}
	else{
		$m->IsMail();
	}

	while($send = $result_send->fetch_array()) {
		$subject = $send['name'];
		$m->CharSet = $charset;
			
		if($charset != 'utf-8'){
			$subject = iconv('utf-8',$charset,$subject);
		}
			
		$m->Subject = $subject;		
			
		if($send['prior'] == "1") 
			$m->Priority = 1;
		else if($send['prior'] == "2") 
			$m->Priority = 5;
		else $m->Priority = 3;

		if($settings['show_email'] == "no") 
			$m->From = "noreply@".$_SERVER['SERVER_NAME']."";
		else 
			$m->From = $settings['email'];	
					
		$m->FromName = $from;

		if($settings['content_type'] == 2) 
			$m->isHTML(true);
		else	
			$m->isHTML(false);			
			
		if($settings['interval_type'] == 'm')
			$interval = "AND (time_send < NOW() - INTERVAL '".$settings['interval_number']."' MINUTE)";
		else if($settings['interval_type'] == 'h')
			$interval = "AND (time_send < NOW() - INTERVAL '".$settings['interval_number']."' HOUR)";
		else if($settings['interval_type'] == 'd')
			$interval = "AND (time_send < NOW() - INTERVAL '".$settings['interval_number']."' DAY)";
		else  
			$interval = '';

		$limit = $settings['make_limit_send'] == "yes" ? "LIMIT ".$settings['limit_number']."" : "";			
	
		if($settings['re_send'] == "no") { 
			if($send['id_cat'] == 0) 
				$query_users = "SELECT *,u.id_user as id FROM ".$PNSL["config"]["db"]["prefix"]."users u 
								LEFT JOIN ".$PNSL["config"]["db"]["prefix"]."ready_send r ON u.id_user=r.id_user AND r.id_template=".$send['id_template']." 
								WHERE (r.id_user IS NULL) AND (status='active') ".$interval." ".$limit."";		
			else 
				$query_users = "SELECT *,u.id_user as id FROM ".$PNSL["config"]["db"]["prefix"]."users u 
								LEFT JOIN ".$PNSL["config"]["db"]["prefix"]."subscription s ON u.id_user=s.id_user
								LEFT JOIN ".$PNSL["config"]["db"]["prefix"]."ready_send r ON u.id_user=r.id_user AND r.id_template=".$send['id_template']." 
								WHERE (r.id_user IS NULL) AND (id_cat=".$send['id_cat'].") AND (status='active') ".$interval." 
								".$limit."";									
		}
		else{
			if($send['id_cat'] == 0) 
				$query_users = "SELECT *,id_user as id FROM ".$PNSL["config"]["db"]["prefix"]."users WHERE status='active' ".$interval." ".$limit."";
			else 
				$query_users = "SELECT *,u.id_user as id FROM ".$PNSL["config"]["db"]["prefix"]."users u 
								LEFT JOIN ".$PNSL["config"]["db"]["prefix"]."subscription s ON u.id_user=s.id_user 
								WHERE (id_cat=".$send['id_cat'].") AND (status='active') ".$interval."
								".$limit."";	
		}						
		
		$result_users = $dbh->query($query_users);
		
		if(!$result_users) exit('Error executing SQL query!');
		
		while($user = $result_users->fetch_array()){

			if(getStatusProcess() == 'stop' OR getStatusProcess() == 'pause') break;
			if($settings['sleep'] and $settings['sleep'] > 0) sleep($settings['sleep']);
			if(!empty($settings['organization'])) $m->addCustomHeader("Organization: ".$settings['organization']."");
			
			$IMG = '<img border="0" src="http://'.$_SERVER["SERVER_NAME"].root().'?task=pic&id_user='.$user['id'].'&id_template='.$send['id_template'].'" width="1" height="1">';
			
			$m->AddAddress($user['email']);

			if($settings['request_reply'] == 'yes' and !empty($settings['email'])){
				$m->addCustomHeader("Disposition-Notification-To: ".$settings['email']."");
				$m->ConfirmReadingTo = $settings['email'];
			}			

			if($settings['precedence'] == 'bulk') 
				$m->addCustomHeader("Precedence: bulk");
			else if($settings['precedence'] == 'junk')
				$m->addCustomHeader("Precedence: junk");
			else if($settings['precedence'] == 'list')
				$m->addCustomHeader("Precedence: list");				
				
			$UNSUB = "http://".$_SERVER["SERVER_NAME"].root()."?task=unsubscribe&id=".$user['id']."&token=".$user['token']."";
			$unsublink = str_replace('%UNSUB%', $UNSUB, $settings['unsublink']);

			if($settings['show_unsubscribe_link'] == "yes" and !empty($settings['unsublink'])) { 
				$msg = "".$send['body']."<br><br>".$unsublink.""; 
				$m->addCustomHeader("List-Unsubscribe: ".$UNSUB."");
			}
			else $msg = $send['body'];				

			$msg = str_replace('%NAME%', $user['name'], $msg);
			$msg = str_replace('%UNSUB%', $UNSUB, $msg);
			$msg = str_replace('%SERVER_NAME%', $_SERVER['SERVER_NAME'], $msg);
			$msg = str_replace('%USERID%', $user['id'], $msg);				
				
			$query = "SELECT * FROM ".$PNSL["config"]["db"]["prefix"]."attach WHERE id_template=".$send['id_template'];
		
			$result_attach = $dbh->query($query);
				
			while($row = $result_attach->fetch_array()){
				if($fp = @fopen($row['path'],"rb")){
					$file = fread($fp, filesize($row['path']));

					fclose($fp);

					if($charset != 'utf-8') $row['name'] = iconv('utf-8',$charset,$row['name']);

					$ext = strrchr($row['path'], ".");
					$mime_type = get_mime_type($ext);

					$m->AddAttachment($row['path'], $row['name'], 'base64', $mime_type);
				}					
			}
				
			$result_attach->close();

			if($charset != 'utf-8') $msg = iconv('utf-8', $charset, $msg);
				
			if($settings['content_type'] == 2){
				$msg .= $IMG;
			}
			else{
				$msg = preg_replace('/<br(\s\/)?>/i', "\n", $msg);
				$msg = remove_html_tags($msg);
			}	
				
			$m->Body = $msg;	

			if(!$m->Send()){
				$errormsg = $m->ErrorInfo;							
						
				$insert = "INSERT INTO ".$PNSL["config"]["db"]["prefix"]."ready_send (`id_ready_send`,`id_user`,`id_template`,`success`,`errormsg`,`readmail`,`time`,`id_log`) VALUES (0,".$user['id'].",".$send['id_template'].",'no','".$errormsg."','no',now(),".$id_log.")";
				$dbh->query($insert);
				$mailcountno = $mailcountno+1;
			}
			else{
				$insert = "INSERT INTO ".$PNSL["config"]["db"]["prefix"]."ready_send (`id_ready_send`,`id_user`,`id_template`,`success`,`errormsg`,`readmail`,`time`,`id_log`) VALUES (0,".$user['id'].",".$send['id_template'].",'yes','','no',now(),".$id_log.")";
				$dbh->query($insert);
				
				$update = "UPDATE ".$PNSL["config"]["db"]["prefix"]."users SET time_send = NOW() WHERE id_user=".$user['id'];
				$dbh->query($update);

				$mailcount = $mailcount+1;						
			}	
				
			$m->ClearCustomHeaders(); 
			$m->ClearAllRecipients();
			$m->ClearAttachments();	

			if($settings['make_limit_send'] == "yes" and $settings['limit_number'] == $mailcount){
				if($settings['how_to_send'] == 2) $m->SmtpClose();
					
				$update = "UPDATE ".$PNSL["config"]["db"]["prefix"]."process SET process='stop'";

				if(!$dbh->query($update)) exit('Error executing SQL query!');
					
				break;
			}
		}
			
		if($settings['make_limit_send'] == "yes" and $settings['limit_number'] == $mailcount){
			if($settings['how_to_send'] == 2) $m->SmtpClose();
				
			$update = "UPDATE ".$PNSL["config"]["db"]["prefix"]."process SET process='stop'";

			if(!$dbh->query($update)) exit('Error executing SQL query!');

			break;
		}				
	}
}

$result_send->close();

$update = "UPDATE ".$PNSL["config"]["db"]["prefix"]."process SET process='stop'";

if(!$dbh->query($update)) exit('Error executing SQL query!');

$dbh->close();

function getStatusProcess()
{
	global $PNSL;
	global $dbh;

	$query = "SELECT * FROM ".$PNSL["config"]["db"]["prefix"]."process";
	
	$result = $dbh->query($query);
	
	$row = $result->fetch_array();
	
	return $row['process'];
}

?>