<?php

/********************************************
* PHP Newsletter 4.2.11
* Copyright (c) 2006-2016 Alexander Yanitsky
* Website: http://janicky.com
* E-mail: janickiy@mail.ru
* Skype: janickiy
********************************************/

if(empty($_GET['id'])) error($PNSL["lang"]["error"]["unsubscribe"]);
if(empty($_GET['token'])) error($PNSL["lang"]["error"]["unsubscribe"]);

if($_GET['token'] != 'test') $token = $data->getToken();

$error = null;

if($token == $_GET['token']){
	$result = $data->makeUnsubscribe();
	
	if(!$result) 
		$error = true;
	else	
		$error = false;
}
else if($_GET['token'] == 'test') 
	$error = false;
else 
	$error = true;

if(!$error){ 
	echo '<!DOCTYPE html>';
	echo "<html>\n";
	echo "<head>\n";
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\n";
	echo "<title>".$PNSL["lang"]["str"]["title_unsubscribe"]."</title>\n";
	echo "</head>\n";
	echo "<body>\n";
	echo "<center>".$PNSL["lang"]["msg"]["subscribe_removed"]."</center>\n";
	echo "</body>\n";
	echo "</html>";	
}
else error($PNSL["lang"]["error"]["unsubscribe"]);