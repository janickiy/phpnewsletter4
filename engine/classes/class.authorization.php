<?php

/********************************************
* PHP Newsletter 4.2.11
* Copyright (c) 2006-2016 Alexander Yanitsky
* Website: http://janicky.com
* E-mail: janickiy@mail.ru
* Skype: janickiy
********************************************/

class Auth
{
	static function authorization()
	{
		session_start();
		
		global $PNSL;
		
		$db = new DBParser($PNSL);
	
		$query = "SELECT * FROM ".$db->getTableName('aut')."";
		$result = $db->querySQL($query);
		$row = $db->getRow($result);	
		
		if($_POST['admin']){
			if($_SESSION['sess_admin'] != "ok")	$sess_pass = md5(trim($_POST['password']));
			
			if($sess_pass === $row['passw']){
				$_SESSION['sess_admin'] = "ok";
			}
			else{
				echo '<!DOCTYPE html>
				<html>
				<head>
				<title>'.$PNSL["lang"]["title"]["error_authorization"].'</title>
				<meta http-equiv="content-type" content="text/html; charset=utf-8">
				</head>
				<body>
				<script type="text/javascript">
				window.alert(\''.$PNSL["lang"]["alert"]["not_authorized"].'\');
				window.location.href=\''.$_SERVER['PHP_SELF'].'\';
				</script>
				</body>
				</html>';
				
				exit();
			}
		}
		else{
			if($_SESSION['sess_admin'] != "ok"){			
				// require temlate class
				require_once $PNSL["system"]["dir_root"].$PNSL["system"]["dir_libs"]."html_template/SeparateTemplate.php";
				$tpl = SeparateTemplate::instance()->loadSourceFromFile($PNSL["system"]["template"]."authorization.tpl");

				$tpl->assign('TITLE', $PNSL["lang"]["title"]["authorization"]);
				$tpl->assign('STR_ADMIN_AREA', $PNSL["lang"]["str"]["admin_area"]);
				$tpl->assign('SCRIPT_NAME', $PNSL["lang"]["script"]["name"]);
				$tpl->assign('STR_SIGN_IN', $PNSL["lang"]["str"]["sign_in"]);
				$tpl->assign('STR_PASSWORD', $PNSL["lang"]["str"]["password"]);			
				
				// display content
				$tpl->display();
				
				exit();
			}
		}		
	}
	
	static function getCurrentHash()
	{
		global $PNSL;
		
		$db = new DBParser($PNSL);
		
		$query = "SELECT * FROM ".$db->getTableName('aut')."";
		$result = $db->querySQL($query);
		$row = $db->getRow($result);
		
		return $row['passw'];
	}	

	static function checkLicenseKey()
	{
		global $PNSL;
		
		if($_SERVER['REMOTE_ADDR'] == '127.0.0.1'){
			return true;
		}
		else{
			$db = new DBParser($PNSL);
		
			$query = "SELECT * FROM ".$db->getTableName('licensekey')."";
			$result = $db->querySQL($query);
			$row = $db->getRow($result);
		
			$licensekey = trim(strtolower($row['licensekey']));	
			
			$str = self::getLicenseKeyData($licensekey);

			preg_match("/<result>([^<]+)<\/result>/i", $str, $out);
		
			if($out[1] == 'yes'){
				return true;
			}
			elseif($out[1] == 'no'){
				return false;
			}
			else{
				return true;
			}
		}		
	}
	
	static function getLicenseKeyType()
	{
		global $PNSL;
		
		$db = new DBParser($PNSL);
		
		$query = "SELECT * FROM ".$db->getTableName('licensekey')."";
		$result = $db->querySQL($query);
		$row = $db->getRow($result);

		$licensekey = trim(strtolower($row['licensekey']));	
			
		$str = self::getLicenseKeyData($licensekey);

		preg_match("/<license_type>([^<]+)<\/license_type>/i", $str, $out);
		
		return $out[1];		
	}
	
	static function getLicenseKeyData($licensekey)
	{
		list($x1, $x2) = explode('.', strrev($_SERVER['SERVER_NAME']));
		$domain = $x1.'.'.$x2;
		$domain = strrev($domain);
	
		$headers = "GET /scripts/check_licensekey.php?licensekey=".$licensekey."&domain=".$domain." HTTP/1.1\r\n";
		$headers .= "Host: janicky.com\r\n";
		$headers .= "Accept: */*\r\n";
		$headers .= "Accept-Charset: utf-8;q=0.7,*;q=0.7\r\n";
		$headers .= "Connection: Close\r\n\r\n";	

		$str = '';
		
		$fp = @fsockopen('janicky.com', 80, $errno, $errstr, 30);

		fwrite($fp, $headers);

		while (!feof($fp))
		{
			$str .= fgets($fp, 1024);
		}
		
		return $str;
	}
}

?>