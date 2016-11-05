<?php

/********************************************
* PHP Newsletter 4.2.11
* Copyright (c) 2006-2016 Alexander Yanitsky
* Website: http://janicky.com
* E-mail: janickiy@mail.ru
* Skype: janickiy
********************************************/

class Model_sendtest extends Model
{
	private $data;

	public function __construct() {
		$this->get_data();
	}

	public function get_data()
	{
		global $PNSL;
		$this->data = new DBParser($PNSL);
		
		return $this->data;
	}
	
	public function sendTestEmail($email,$subject,$body,$prior)
	{
		global $PNSL;
		
		$user = 'USERNAME';
		
		$subject = str_replace('%NAME%', $user['name'], $subject);
		
		require_once $PNSL["system"]["dir_root"].$PNSL["system"]["dir_libs"]."PHPMailer/class.phpmailer.php";
		
		$query = "SELECT * FROM ".$this->data->getTableName('settings')."";
		$result = $this->data->querySQL($query);
		$settings = $this->data->getRow($result);
		
		$m = new PHPMailer();
		
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
		
		$query = "SELECT * FROM ".$this->data->getTableName('charset')." WHERE id_charset=".$settings['id_charset'];
		$result = $this->data->querySQL($query);
		$char = $this->data->getRow($result);
		$charset = $char['charset'];
		
		$m->CharSet = $charset;

		if(empty($settings['email_name']))
			$from = $_SERVER["SERVER_NAME"];
		else
			$from = $settings['email_name'];

		if($charset != 'utf-8') {
			$from = iconv('utf-8', $charset, $from);
			$subject = iconv('utf-8', $charset, $subject);
			if($settings['organization']) $settings['organization'] = iconv('utf-8', $charset, $settings['organization']);
		}	
		
		$m->Subject = $subject;
		if(!empty($settings['organization'])) $m->addCustomHeader("Organization: ".$settings['organization']."");
		
		if($prior == 1) 
			$m->Priority = 1;
		else if($prior == 2) 
			$m->Priority = 2;
		else 
			$m->Priority = 3;

		if($settings['show_email'] == "no") 
			$m->From = "noreply@".$_SERVER['SERVER_NAME']."";
		else 
			$m->From = $settings['email'];	
					
		$m->FromName = $from;
	
		if($settings['content_type'] == 2) 
			$m->isHTML(true);
		else	
			$m->isHTML(false);	
	
		$m->AddAddress($email);	
	
		if($settings['request_reply'] and !empty($settings['email_reply'])){
			$m->addCustomHeader("Disposition-Notification-To: ".$settings['email_reply']."");
			$m->ConfirmReadingTo = $settings['email_reply'];
		}

		if($settings['precedence'] == 'bulk') 
			$m->addCustomHeader("Precedence: bulk");
		else if($settings['precedence'] == 'junk')
			$m->addCustomHeader("Precedence: junk");
		else if($settings['precedence'] == 'list')
			$m->addCustomHeader("Precedence: list");				
				
		$UNSUB = "http://".$_SERVER["SERVER_NAME"].root()."?task=unsubscribe&id=test&token=test";
		$unsublink = str_replace('%UNSUB%', $UNSUB, $settings['unsublink']);

		if($settings['unsubscribe'] == "yes" and !empty($settings['unsublink'])) { 
			$msg = "".$body."<br><br>".$unsublink.""; 
			$m->addCustomHeader("List-Unsubscribe: ".$UNSUB."");
		}
		else $msg = $body;
	
		$msg = str_replace('%NAME%', $user, $msg);
		$msg = str_replace('%UNSUB%', $UNSUB, $msg);
		$msg = str_replace('%SERVER_NAME%', $_SERVER['SERVER_NAME'], $msg);
		$msg = str_replace('%USERID%', 0, $msg);
	
		if($charset != 'utf-8') $msg = iconv('utf-8', $charset, $msg);
		
		if($settings['content_type'] == 1){
			$msg = preg_replace('/<br(\s\/)?>/i', "\n", $msg);
			$msg = remove_html_tags($msg);
		}
		
		$m->Body = $msg;	
	
		if(!$m->Send()){
			if($settings['how_to_send'] == 2) $m->SmtpClose();
			return false;
		}
		else{
			if($settings['how_to_send'] == 2) $m->SmtpClose();
			return true;
		}
	}
}