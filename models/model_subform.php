<?php

/********************************************
* PHP Newsletter 4.1.3
* Copyright (c) 2006-2015 Alexander Yanitsky
* Website: http://janicky.com
* E-mail: janickiy@mail.ru
* Skype: janickiy
********************************************/

class Model_subform extends Model
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
	
	public function getCategoryList()
	{
		$query = "SELECT * FROM ".$this->data->getTableName('category')." ORDER BY name";
		$result = $this->data->querySQL($query);
		return $this->data->getColumnArray($result);
	}	
	
	public function makeSubscribe($fields)
	{
		return $this->data->insert($fields, $this->data->getTableName('users'));
	}
	
	public function checkExistEmail()
	{
		$query = "SELECT * FROM ".$this->data->getTableName('users')." WHERE email LIKE '".$_POST['email']."'";
		$result = $this->data->querySQL($query);
		
		return $this->data->getRecordCount($result);		
	}
	
	public function insertSubs($id_user)
	{
		if($_POST['id_cat']){		
			foreach($_POST['id_cat'] as $id_cat){
				if(preg_match("|^[\d]+$|",$id_cat)){				
					$fields = array();
					$fields['id_sub'] = 0;
					$fields['id_user'] = $id_user;
					$fields['id_cat'] = $id_cat;
					
					$result = $this->data->insert($fields, $this->data->getTableName('subscription'));				
				}
			}
		}
	}
	
	public function sendNotification($id_user,$token)
	{
		global $PNSL;
		
		if($id_user and $token){
			require_once $PNSL["system"]["dir_root"].$PNSL["system"]["dir_libs"]."PHPMailer/class.phpmailer.php";
	
			$query = "SELECT * FROM ".$this->data->getTableName('settings')."";
			$result = $this->data->querySQL($query);
			$settings = $this->data->getRow($result);
			
			$query = "SELECT * FROM ".$this->data->getTableName('charset')." WHERE id_charset = ".$settings['id_charset'];
			$result = $this->data->querySQL($query);
			$char = $this->data->getRow($result);
			$charset = $char['charset'];			
		
			$UNSUB = "http://".$_SERVER["SERVER_NAME"].root()."?task=unsubscribe&id=".$id_user."&token=".$token."";
			$CONFIRM = "http://".$_SERVER["SERVER_NAME"].root()."?task=subscribe&id=".$id_user."&token=".$token."";
	
			$settings['textconfirmation'] = str_replace('%NAME%', $_POST['name'], $settings['textconfirmation']);
			$settings['textconfirmation'] = str_replace('%CONFIRM%', $CONFIRM, $settings['textconfirmation']);
			$settings['textconfirmation'] = str_replace('%UNSUB%', $UNSUB, $settings['textconfirmation']);
			$settings['textconfirmation'] = str_replace('%SERVER_NAME%', $_SERVER['SERVER_NAME'], $settings['textconfirmation']);
			
			$from = $settings['email_name'] == '' ? $_SERVER["SERVER_NAME"] : $settings['email_name'];
			
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
			
			$m->CharSet = $charset;
			
			if($charset != 'utf-8') {
				$settings['textconfirmation'] = iconv('utf-8', $charset, $settings['textconfirmation']);
				$settings['subjecttextconfirm'] = iconv('utf-8', $charset, $settings['subjecttextconfirm']);
				if(!empty($settings['organization'])) $settings['organization'] = iconv('utf-8', $charset, $settings['organization']);
				$from_mail = iconv('utf-8', $charset, $from_mail);
			}
			
			$m->Subject = $settings['subjecttextconfirm'];
			
			if($settings['show_email'] == "no") 
				$m->From = "noreply@".$_SERVER['SERVER_NAME']."";
			else 
				$m->From = $settings['email'];
				
			$m->FromName = $from;
			
			$m->AddAddress($_POST['email']);
			
			if(!empty($settings['organization'])) $m->addCustomHeader("Organization: ".$settings['organization']."");
				
			$m->isHTML(false);
			$m->Body = $settings['textconfirmation'];
			$m->Send();
			$m->ClearCustomHeaders(); 
			$m->ClearAllRecipients();
			
			if($settings['newsubscribernotify'] == 'yes'){
				if($charset != 'utf-8') 
					$subject = iconv('utf-8', $charset, $PNSL["lang"]["subject"]["notification_newuser"]);
				else
					$subject = $PNSL["lang"]["subject"]["notification_newuser"];
	
				$msg = "".$PNSL["lang"]["msg"]["notification_newuser"]."\nName: ".$_POST['name']." \nE-mail: ".$_POST['email']."\n";
				$msg = str_replace('%SITE%', $_SERVER['SERVER_NAME'], $msg);
		
				if($charset != 'utf-8') $msg = iconv('utf-8', $charset,$msg);

				$m->From = $settings['email'];
				$m->AddAddress($settings['email']);				
				$m->Subject = $subject;
				$m->Body = $msg;
				$m->Send();
			}
			
			if($settings['how_to_send'] == 2) $m->SmtpClose();
			
			return true;
		}
		else return false;
	}
	
	public function getSetting(){
		$query = "SELECT * FROM ".$this->data->getTableName('settings')."";
		$result = $this->data->querySQL($query);
		
		return $this->data->getRow($result);	
	}
}

?>