<?php

/********************************************
* PHP Newsletter 4.1.3
* Copyright (c) 2006-2015 Alexander Yanitsky
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

				echo '<!DOCTYPE html>
				<html>
				<head>
				<title>'.$PNSL["lang"]["title"]["authorization"].'</title>
				<meta http-equiv="content-type" content="text/html; charset=utf-8">
				<link href="./styles/bootstrap.min.css" rel="stylesheet" media="screen">
				<link href="./styles/bootstrap-responsive.min.css" rel="stylesheet" media="screen">
				<link href="./styles/styles.css" rel="stylesheet" media="screen">
				<link href="./styles/DT_bootstrap.css" rel="stylesheet" media="screen">				
				</head>
				<body>				
				<div class="container">
				<div class="row ">
				<div class="row text-center ">
				<div class="col-md-12">
				<br>
				<br>
				<h2>'.$PNSL["lang"]["str"]["admin_area"].' '.$PNSL["lang"]["script"]["name"].'</h2>
				<br>
				</div>
				</div>
				<form class="form-signin" method="post">
				<h4 class="form-signin-heading">'.$PNSL["lang"]["str"]["sign_in"].'</h4>
				<input class="input-block-level" type="password" name="password" placeholder="'.$PNSL["lang"]["str"]["password"].'">
				<input type="submit" class="btn btn-primary" name="admin" value=" OK ">
				</form>
				</div>
				</body>
				</html>';
				
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