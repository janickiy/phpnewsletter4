<?php

/********************************************
* PHP Newsletter 4.0.16
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
				<form class="form-signin" method="post">
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
}

?>