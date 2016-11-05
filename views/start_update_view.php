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

$update = new Update();
$newversion = $update->getVersion();
$currentversion = $PNSL["system"]["version"];

session_write_close();

$path = $PNSL["system"]["dir_tmp"].'update.zip';

header('Content-Type: application/xml; charset=utf-8');
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
echo "<document>\n";

if($_REQUEST['p'] == 'start' and Auth::checkLicenseKey()){
	$result = $data->DownloadUpdate($path, 'http://download.janicky.com/update/phpnewsletter/update.zip');
	
	if($result){
		echo "<status>".$PNSL["lang"]["str"]["download_completed"]."</status>\n";
		echo "<result>yes</result>\n";
	}
	else{
		echo "<status>".$PNSL["lang"]["error"]["failed_to_update"]."</status>\n";
		echo "<result>no</result>\n";
	}
}

if($_REQUEST['p'] == 'update_files' and Auth::checkLicenseKey()){
	if(is_file($path)){
		$arc = new Unzipper($path);
		echo "<status>".$arc::$status."</status>\n";
		echo "<result>".$arc::$result."</result>\n";
	}
}

if($_REQUEST['p'] == 'update_bd' and Auth::checkLicenseKey()){
	$current_version_code = get_current_version_code($currentversion);	
	$version_code_detect = $data->version_code_detect();
	
	if($version_code_detect < $current_version_code){
		$settings = $data->getSetting();	
	
		if($version_code_detect == 40000){
			$path_update = 'tmp/update/update_4_0_'.$settings['language'].'.sql';	
		}

		if(is_file($path_update)){
			$result = $data->updateDB($path_update);
		
			if($result){
				echo "<status>".$PNSL["lang"]["msg"]["update_completed"]."</status>\n";
				echo "<result>yes</result>\n";
			}
			else{
				echo "<status>".$PNSL["lang"]["error"]["failed_to_update"]."</status>\n";
				echo "<result>no</result>\n";
			}
		}
	}
	else{
		echo "<status>".$PNSL["lang"]["msg"]["update_completed"]."</status>\n";
		echo "<result>yes</result>\n";	
	}
}

echo "</document>";

?>