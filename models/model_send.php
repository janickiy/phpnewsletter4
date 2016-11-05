<?php

/********************************************
* PHP Newsletter 4.1.3
* Copyright (c) 2006-2015 Alexander Yanitsky
* Website: http://janicky.com
* E-mail: janickiy@mail.ru
* Skype: janickiy
********************************************/

class Model_send extends Model
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
	
	public function getStatusProcess()
	{
		$query = "SELECT * FROM ".$this->data->getTableName('process')."";
		$result = $this->data->querySQL($query);
		$row = $this->data->getRow($result, 'array');
	
		return $row['process'];
	}
	
	public function updateProcess($status)
	{
		$query = "UPDATE ".$this->data->getTableName('process')." SET process='".$status."'";
		return $this->data->querySQL($query);
	}	
	
	public function SendEmails()
	{
		global $PNSL;
		
		$fh = fopen(__FILE__, 'r');

		if(!flock($fh, LOCK_EX | LOCK_NB)){
			exit('Script is already running');
		}
		
		$mailcountno = 0;
		$mailcount = 0;
		
		require_once $PNSL["system"]["dir_root"].$PNSL["system"]["dir_libs"]."PHPMailer/class.phpmailer.php";
		
		$query = "SELECT * FROM ".$this->data->getTableName('settings')."";
		$result = $this->data->querySQL($query);
		$settings = $this->data->getRow($result);	

		$fields = array();
		$fields['id_log'] = 0;	
		$fields['time'] = date("Y-m-d H:i:s");
		
		$insert_id = $this->data->insert($fields, $this->data->getTableName('log'));
		
		$query = "SELECT * FROM ".$this->data->getTableName('charset')." WHERE id_charset=".$settings['id_charset'];
		$result = $this->data->querySQL($query);		
		$char = $this->data->getRow($result);	
		$charset = $char['charset'];
		
		if($charset != 'utf-8') {
			$from = iconv('utf-8',$charset,$from);
			if(!empty($settings['organization'])) $settings['organization'] = iconv('utf-8',$charset,$settings['organization']);
		}	
		
		$from = $settings['email_name'] == '' ? $_SERVER["SERVER_NAME"] : $settings['email_name'];

		$temp = array();

		foreach($_REQUEST['activate'] as $id_template){
			if(preg_match("|^[\d]+$|", $id_template)){
				$temp[] = $id_template;
			}
		}
		
		$query = "SELECT * FROM ".$this->data->getTableName('template')." WHERE active='yes' AND id_template IN (".implode(",",$temp).")";
		$result = $this->data->querySQL($query);
		
		if($this->data->getRecordCount($result) > 0){
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
		
			while($send = $this->data->getRow($result))
			{			
				$subject = $send['name'];
				$m->CharSet = $charset;
			
				if($charset != 'utf-8'){
					$subject = iconv('utf-8',$charset,$subject);
				}
			
				$m->Subject = $subject;		
			
				if($send['prior'] == 1) 
					$m->Priority = 1;
				else if($send['prior'] == 2) 
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
			
				if($_GET['typesend'] == 2){
					if($send['id_cat'] == 0) 
						$query_users = "SELECT *,u.id_user as id FROM ".$this->data->getTableName('users')." u 
						LEFT JOIN ".$this->data->getTableName('ready_send')." r ON (u.id_user=r.id_user) AND (r.success='yes') AND (r.id_template=".$send['id_template'].")
						WHERE (r.id_user IS NULL) AND (status='active') ".$limit."";
					else	
						$query_users = "SELECT *,u.id_user as id FROM ".$this->data->getTableName('users')." u
						LEFT JOIN ".$this->data->getTableName('subscription')." s ON u.id_user=s.id_user
						LEFT JOIN ".$this->data->getTableName('ready_send')." r ON (u.id_user=r.id_user) AND (r.success='yes') AND (r.id_template=".$send['id_template'].")
						WHERE (r.id_user IS NULL) AND (id_cat=".$send['id_cat'].") AND (status='active') ".$limit."";
				}
				else{
					if($settings['re_send'] == "no") { 
						if($send['id_cat'] == 0) 
							$query_users = "SELECT *,u.id_user as id FROM ".$this->data->getTableName('users')." u 
							LEFT JOIN ".$this->data->getTableName('ready_send')." r ON u.id_user=r.id_user AND r.id_template=".$send['id_template']." 
							WHERE (r.id_user IS NULL) AND (status='active') ".$interval." ".$limit."";		
						else 
							$query_users = "SELECT *,u.id_user as id FROM ".$this->data->getTableName('users')." u 
							LEFT JOIN ".$this->data->getTableName('subscription')." s ON u.id_user=s.id_user
							LEFT JOIN ".$this->data->getTableName('ready_send')." r ON u.id_user=r.id_user AND r.id_template=".$send['id_template']." 
							WHERE (r.id_user IS NULL) AND (id_cat=".$send['id_cat'].") AND (status='active') ".$interval." 
							".$limit."";									
					}
					else{
						if($send['id_cat'] == 0) 
							$query_users = "SELECT *,id_user as id FROM ".$this->data->getTableName('users')." WHERE status='active' ".$interval." ".$limit."";
						else 
							$query_users = "SELECT *,u.id_user as id FROM ".$this->data->getTableName('users')." u 
							LEFT JOIN ".$this->data->getTableName('subscription')." s ON u.id_user=s.id_user 
							WHERE (id_cat=".$send['id_cat'].") AND (status='active') ".$interval."
							".$limit."";	
					}						
				}
			
				$result_users = $this->data->querySQL($query_users);		
		
				while($user = $this->data->getRow($result_users))
				{	
					if($this->getStatusProcess() == 'stop' OR $this->getStatusProcess() == 'pause') break;
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
				
					$query = "SELECT * FROM ".$this->data->getTableName('attach')." WHERE id_template=".$send['id_template'];
					$result_attach = $this->data->querySQL($query);
				
					while($row = $this->data->getRow($result_attach))
					{
						if($fp = @fopen($row['path'],"rb")){
							$file = fread($fp, filesize($row['path']));

							fclose($fp);

							if($charset != 'utf-8') $row['name'] = iconv('utf-8',$charset,$row['name']);

							$ext = strrchr($row['path'], ".");
							$mime_type = get_mime_type($ext);

							$m->AddAttachment($row['path'], $row['name'], 'base64', $mime_type);
						}					
					}				

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
						$fields = array();
						$fields['id_ready_send'] = 0;
						$fields['id_user'] = $user['id'];
						$fields['id_template'] = $send['id_template'];
						$fields['success'] = 'no';
						$fields['errormsg'] = $m->ErrorInfo;
						$fields['readmail'] = 'no';
						$fields['time'] = date("Y-m-d H:i:s");
						$fields['id_log'] = $insert_id;
					
						$insert = $this->data->insert($fields, $this->data->getTableName("ready_send"));
						$mailcountno = $mailcountno+1;
					}
					else{
						$fields = array();
						$fields['id_ready_send'] = 0;
						$fields['id_user'] = $user['id'];
						$fields['id_template'] = $send['id_template'];
						$fields['success'] = 'yes';
						$fields['errormsg'] = '';
						$fields['readmail'] = 'no';
						$fields['time'] = date("Y-m-d H:i:s");
						$fields['id_log'] = $insert_id;
					
						$insert = $this->data->insert($fields, $this->data->getTableName("ready_send"));
					
						$query = "UPDATE ".$this->data->getTableName("users")." SET time_send = NOW() WHERE id_user=".$user['id'];
						$update = $this->data->querySQL($query);

						$mailcount = $mailcount+1;						
					}	
				
					$m->ClearCustomHeaders(); 
					$m->ClearAllRecipients();
					$m->ClearAttachments();	

					if($settings['make_limit_send'] == "yes" and $settings['limit_number'] == $mailcount){
						if($settings['how_to_send'] == 2) $m->SmtpClose();
						$result = $this->updateProcess('stop');
						return $mailcount;
						break;
					}
				}			
			}
			
			if($settings['make_limit_send'] == "yes" and $settings['limit_number'] == $mailcount){
				if($settings['how_to_send'] == 2) $m->SmtpClose();
				$result = $this->updateProcess('stop');
				return $mailcount;
				break;
			}
		}
		
		$result = $this->updateProcess('stop');

		return $mailcount;	
	}
}

?>